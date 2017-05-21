<html>
<head>
    <meta charset='UTF-8'>
    <title>测试</title>
</head>
<body>
<?php
    // NOTICE: This file contains debug code. Please comment it before formal release for security reasons.

    ini_set("display_errors", "On"); //debug

    define('EFZ_DB_NAME', 'data'); //DB Name
    define('EFZ_DB_USER', 'test'); //Your database username
    define('EFZ_DB_PASSWORD', 'test'); //Your database users password
    define('EFZ_DB_HOST', 'localhost'); //The hostname for your database
    define('EFZ_DB_PORT', '3306');

    try {
        $dbh = new PDO('mysql:host='.EFZ_DB_HOST.';port='.EFZ_DB_PORT.'; dbname='.EFZ_DB_NAME,
            EFZ_DB_USER,EFZ_DB_PASSWORD,array(PDO::ATTR_PERSISTENT=>true)
        );
        $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $dbh->query("SET NAMES utf8");
        $dbh->query("SET sql_mode = ''");
    } catch(PDOException $e) {
        die("Error connecting to the database: ".$e->getMessage());
    }
    echo 'DB Connection Success.<br>';

    function efz_do_query($query,$params,$dbh){
        $sth = $dbh->prepare($query);
        try{
            $sth->execute($params);
        }catch(PDOException $e) {
            $sth->debugDumpParams(); //debug
            die("Query Failed: ".$e->getMessage()); //debug
        }
        return $sth;
    }

    function efz_do_fetch_result($sth){
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    function efz_do_fetch_result_all($sth){
        return $sth->fetchAll();
    }

    $query = "SELECT * FROM test";
    $params = null;
    $sth = efz_do_query($query,$params,$dbh);
    echo '<table border = "0">';
    while(true){
        $row = efz_do_fetch_result($sth);
        if ($row == null) {break;}
        echo '<tr>';
        foreach ($row as $tmp){
            echo '<td>'.$tmp.'</td>';
        }
        echo '</tr>';
    }
    echo '</table>';

    $dbh = null;

    /*if(!empty($_GET['sortby'])){
		$form_sort_by = strtolower(trim($_GET['sortby'])); //the user select a new sort order

		//save the sort order into ap_form_sorts table
		$query = "delete from "._TABLE_PREFIX."form_sorts where user_id=?";
		$params = array($_SESSION['_user_id']);
		mf_do_query($query,$params,$dbh);

		$query = "insert into "._TABLE_PREFIX."form_sorts(user_id,sort_by) values(?,?)";
		$params = array($_SESSION['_user_id'],$form_sort_by);
		mf_do_query($query,$params,$dbh);

	}else{ //load the previous saved sort order

		$query = "select sort_by from "._TABLE_PREFIX."form_sorts where user_id=?";
		$params = array($_SESSION['_user_id']);

    /******
    "UPDATE "._TABLE_PREFIX."users SET user_password = ? WHERE user_id = ?";
	*/

    /***
     * 抢课解决方案: (SQL)
    start transaction ;//开始事务
    SELECT apply_count into p_apply_count FROM max_table WHERE store_id=p_store_id   for update;
    if (p_apply_count =0 ) THEN
    insert into max_table(store_id,apply_count) values(p_store_id,0);
    end if;
    IF( p_apply_count<=9) THEN

    set encode_apply_code='生成规则',
    insert into apply_form(apply_code,store_id,phone_number) values(encode_apply_code,p_store_id,p_phone_number);
    update max_table set apply_count=apply_count+1 where store_id=p_store_id;
    set result=encode_apply_code2;
    END IF;

    set return_val=result ;
    commit; //提交事务，同时释放for update锁
     */

?>
</body>
</html>
