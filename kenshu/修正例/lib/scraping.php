<?php
require_once dirname(__FILE__).'/config.php';
require_once dirname(__FILE__).'/vendor/simple_html_dom.php';

/**
 * DBの接続を取得
 *
 * @return PDO DBの接続
 */
function get_connection() {
	static $dbh = null;
	global $db_host, $db_name, $db_user, $db_pass; // 設定ファイルで書かれたDBの接続設定を取得

	if (!$dbh) {
		try {
			$dbh = new PDO('mysql:host='.$db_host.'; dbname='.$db_name, $db_user, $db_pass);
		} catch (PDOException $e) {
			var_dump ($e->getMessage());
			exit;
		}
	}
	return $dbh;
}

/**
 * ユーザの作成
 *
 * @param string $username  ユーザ名
 * @param string $userpass  ユーザのパスワード
 * @param string $useremail ユーザのメールアドレス
 * @return bool 成功すれば真、失敗すれば偽
 */
function create_user($username, $userpass, $useremail) {
	$dbh = get_connection();

	$result = $dbh->query("insert into users (name,pass,email) values ('" . $username . "','" . $userpass . "','" . $useremail . "');");

	if ($result) {
		if (!$result = $dbh->query("create table `" . $username . $userpass . "` (id int not null auto_increment, keyword varchar(30) unique, primary key(id));")){
			return false;
		}
		if (!$result = $dbh->query("create table `" . $username . $userpass . "titles` (id int not null auto_increment, title varchar(150) unique, date varchar(30), primary key(id));")) {
			return false;
		}
	}

	return $result;
}

/**
 * ログインチェック
 *
 * DBに与えられたユーザ名/パスワードの組み合わせがあるかを確認
 *
 * @param string $username ユーザ名
 * @param string $userpass ユーザのパスワード
 * @return bool DBに与えられた組み合わせがあれば真、なければ偽
 */
function check_login($username, $userpass) {
	$dbh = get_connection();

	$result = $dbh->query("select count(*) from users where name='" . $username . "' and pass = '" . $userpass . "';")->fetchColumn();

	return $result;
}

/**
 * 全ユーザのリストを取得
 *
 * @return array 全ユーザのDBレコード
 */
function get_all_users() {
	$dbh = get_connection();

	$stmt = $dbh->query("select * from users;");
	$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

	return $users;
}

/**
 * 検索キーワードの登録
 *
 * @param string $username キーワードを登録するユーザ名
 * @param string $userpass キーワードを登録するユーザのパスワード
 * @param string 登録するキーワード
 * @return int 登録されたキーワード数
 */
function register_keyword($username, $userpass, $keywords) {
	$dbh = get_connection();

	$count = 0;
	if ($stmt = $dbh->query("insert into `" . $username . $userpass . "` (keyword) value ('" . $keywords . "');")) {
		$count = $stmt->rowCount();
	}

	return $count;
}

/**
 * 検索キーワードの削除
 *
 * @param string $username キーワードを削除するユーザ名
 * @param string $userpass キーワードを削除するユーザのパスワード
 * @param string 削除するキーワード
 * @return int 削除されたキーワード数
 */
function erase_keyword($username, $userpass, $eraseid) {
	$dbh = get_connection();

	$stmt = $dbh->query("delete from `" . $username . $userpass . "` where id = " . $eraseid);
	$count = $stmt->rowCount();

	return $count;
}

/**
 * 指定ユーザの登録された検索キーワードでニュースを検索し、DBに登録
 *
 * @param string $username 対象ユーザ名
 * @param string $userpass 対象ユーザのパスワード
 * @return array ユーザの登録されているキーワードリスト
 */
function update_news_list($username, $userpass) {
	$dbh = get_connection();

	$result = array();
	if ($stmt = $dbh->query("select * from `" . $username . $userpass . '`')) {
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $seeking){
			$url = urlencode($seeking['keyword']);

			$html = file_get_contents('https://www.google.com/search?hl=ja&gl=jp&tbm=nws&q=' . $url);
			$html = mb_convert_encoding($html, CHAR_SET, "auto");
			$gHtml = str_get_html($html);

			//記事タイトルのインサート
			foreach($gHtml->find('h3') as $titledata){
				$stmt = $dbh->prepare("insert into `" . $username . $userpass . "titles` (title, date) values (?,?)");
				$stmt->execute(array($titledata->plaintext,time()));
			}
		}
	}

	return $result;
}

/**
 * 指定ユーザのDBへ保存されたニュースタイトル一覧を取得
 *
 * @param string $username 対象ユーザ名
 * @param string $userpass 対象ユーザのパスワード
 * @param bool $from_yesterday 偽なら保存済みの全タイトル、真なら24時間前からのものタイトル
 * @return array ニュースタイトル一覧
 */
function get_news_titles($username, $userpass, $from_yesterday = false) {
	$dbh = get_connection();

	$sql = "select * from `" . $username . $userpass . "titles`";
	if ($from_yesterday) {
		// 24時間前のタイムスタンプの取得
		$yesterday = time() - (24 * 60 *60);
		$sql .= " where date >= " . $yesterday;
	}

	$result = array();
	if ($stmt = $dbh->query($sql)) {
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	return $result;
}

/**
 * ユーザへ送るレポートメールの本文を構築
 *
 * @param string $username 対象ユーザ名
 * @param string $userpass 対象ユーザ名のパスワード
 * @return string 構築されたメール本文
 */
function build_report_mail($username, $userpass) {
	$result = update_news_list($username, $userpass);

	$subjkey = '';
	foreach ($result as $sendkey) {
		$subjkey = $subjkey . $sendkey['keyword'] . "\n";
	}

	$titles = get_news_titles($username, $userpass);

	$subjtitles = '';
	foreach ($titles as $sendtitles) {
		$subjtitles = $subjtitles . $sendtitles['title'] . "\n" ;
	}

	ob_start();
	include TEMPLATES_DIR.'mail.php';
	return ob_get_clean();
}

/**
 * ユーザへニュースのレポートメールを送る
 *
 * @param string $username 対象ユーザ名
 * @param string $userpass 対象ユーザ名のパスワード
 * @param string $useremail 対象ユーザ名のメールアドレス
 * @return array メールの送信結果データ
 */
function send_report_mail_to_user($username, $userpass, $useremail) {
	//初期化
	$sTo = '';
	$sSubject = '';
	$sMessage = '';

	//送信先
	$sTo = $useremail;

	//ヘッダー
	$sHeaders = "From: Yamada Taro\r\n";

	//本文
	$sMessage = build_report_mail($username, $userpass);

	$result = array(
		'to' => $useremail,
		'username' => $username,
		'message' => $sMessage
	);

	//送信処理
	if (mb_send_mail($sTo, $sSubject, $sMessage, $sHeaders)) {
		$result['success'] = true;
	} else {
		$result['success'] = false;
	}

	return $result;
}