<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/users', 'getUsers');
$app->get('/users/:token', 'findByToken');
$app->post('users', 'addUser');
$app->put('users/:id', 'updateUser');
$app->delete('users/:id', 'deleteUser');
$app->get('/merchants', 'getMerchants');
$app->delete('/merchants/:id', 'deleteMerchant');
$app->get('/coupons/mid/:id', 'getCouponsByMerchantId');
$app->get('/coupons/uid/:id', 'getCouponsByUserId');

$app->run();

function getUsers() {
	$sql = "select name, email FROM users ORDER BY user_id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);
		$users = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"User": ' . json_encode($users) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function findByToken($token) {
	$sql = "SELECT name, email FROM users WHERE token=:token";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("token", $token);
		$stmt->execute();
		$user = $stmt->fetchObject();
		$db = null;
		echo json_encode($user);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function addUser() {
	error_log('addUser\n', 3, '/var/tmp/php.log');
	$request = Slim::getInstance()->request();
	$user = json_decode($request->getBody());
	$sql = "INSERT INTO users (name, email, password, token, created) VALUES (:name, :email, :password, :token, TIMESTAMP)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("name", $user->name);
		$stmt->bindParam("email", $user->email);
		$stmt->bindParam("password", hash(md5("Hash string"), $user->password));
		$stmt->bindParam("token", hash(md5("Token string"),$user->password));
		$stmt->bindParam("created", $user->created);
		$stmt->execute();
		$user->id = $db->lastInsertId();
		$db = null;
		echo json_encode($user);
	} catch(PDOException $e) {
		error_log($e->getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function updateUser($id) {
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$user = json_decode($body);
	$sql = "UPDATE users SET name=:name, email=:email, password=:password WHERE user_id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("name", $user->name);
		$stmt->bindParam("email", $user->email);
		$stmt->bindParam("password", hash(md5("Hash string"), $user->password));
		$stmt->bindParam("token", hash(md5("Token string"),$user->password));
		$stmt->bindParam("created", $user->created);
		$stmt->bindParam("user_id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($user);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function deleteUser($id) {
	$sql = "DELETE FROM users WHERE user_id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("user_id", $id);
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getMerchants() {
	$sql = "select merchants.name, coupons.title FROM merchants LEFT JOIN coupons ON merchants.merchant_id = coupons.merchant_id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);
		$merchants = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"Merchant": ' . json_encode($merchants) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function deleteMerchant($id) {
	$sql = "DELETE FROM merchants, coupons WHERE merchant_id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("merchant_id", $id);
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getCouponsByMerchantId($id) {
	$sql = "select title, code FROM coupons where merchant_id=$id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);
		$coupons = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"Merchant Coupons": ' . json_encode($coupons) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getCouponsByUserId($id) {
	$sql = "select title, code FROM coupons where user_id=$id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);
		$coupons = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"User Coupons": ' . json_encode($coupons) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getConnection() {
	$dbhost="localhost";
	$dbuser="root";
	$dbpass="";
	$dbname="rest";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

?>