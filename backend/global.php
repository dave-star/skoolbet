
<?php
    function Database ( )
    {
        $conn = Connect ( "localhost", "root", "" );
        mysqli_select_db ( $conn, 'skool' );
        // $conn = Connect ( "localhost", "skoolbet_user", "B*@!{ndxXd{_" );
        // mysqli_select_db ( $conn, 'skoolbet_database' );
        return $conn;
    }
    
    function GetQuery ( $cols, $form )
    {
        $a = '(';
        $b = '(';
        
        for ( $i = 0; $i < count ( $cols ); $i += 1 )
        {
            if ( $cols[$i] != 'date' and $cols[$i] != 'time' )
            {
                if ( $cols[$i] == 'password' )
                {
                    $p = sha1 ( sha1 ( $form[$cols[$i]] ) );
                }
                else
                {
                    $p = $form[$cols[$i]];
                }

                $a .= $cols[$i];
                $b .= "'" . $p . "'";
            }
            else
            {
                $a .= $cols[$i];
                $b .= time ( );
            }
            if ( $i < count ( $cols ) - 1 )
            {
                $a .= ',';
                $b .= ',';
            }
        }

        $a .= ')';
        $b .= ')';

        return [$a, $b];
    }

    function CheckCred ( $cred, $post )
    {
        $cred = explode ( ',', $cred );
        $msg = array (
            "status"    => 1,
            "message"   => "Credentials passed all tests",
        );
        
        for ( $i = 0; $i < count ( $cred ); $i += 1 )
        {
            if ( $cred[$i] == 'email' )
            {
                $msg  = CheckEmail ( $post['email'] );
                // $obj  = json_decode ( $msgx );
                if ( !$msg['status'] )
                {
                    return $msg;
                }
            }
            else if ( $cred[$i] == 'password' )
            {
                $msg  = CheckPassword ( $post['password'], 8 );
                // $obj  = json_decode ( $msgx );
                if ( !$msg['status'] )
                {
                    return $msg;
                }
            }
            else if ( $cred[$i] == 'phone' )
            {
                $msg  = CheckPhone ( $post['phone'] );
                // $msg  = json_decode ( $msgx );
                if ( !$msg['status'] )
                {
                    return $msg;
                }
            }
        }
        
        return $msg;
    }

    function EmailExists ( $email, $conn, $table )
    {
        $value  = "email = '$email'";
        $obj    = DB ( 3, $table, $value, "", $conn, "" );
        if ( mysqli_num_rows ( $obj[1] ) )
        {
            $error = array (
                "status"    => 0,
                "message"   => "Email address already exist!"
            );
        }
        else
        {
            $error = array (
                "status"    => 1,
                "message"   => "Email address is new"
            );
        }

        return $error;
    }

    function RemoveCred ( $table, $conn )
    {
        $cols   = GetColumns ( $conn, $table, [] );
        $index  = array_search ( 'password', $cols );

        array_splice ( $cols, $index, 1 );
        return join ( $cols, ',' );
    }

    function Query ( $form )
    {
        $params = explode ( ':', $form['params'] );
        $value = '';
        $cond = 0;
        for ( $i = 0; $i < count ( $params ); $i += 1 )
        {
            $a = explode ( '|', $params[$i] );
            if ( count ( $a ) != 2 )
            {
                $a = explode ( '&', $params[$i] );
                $cond = 'and';
            }
            else
            {
                $cond = 'or';
            }

            if ( count ( $a ) == 2 )
            {
                $left = $form[$a[0]];
                $right = $form[$a[1]];

                if ( $a[0] == 'password' )
                {
                    $left = sha1 ( sha1 ( $left ) );
                }
                else if ( $a[1] == 'password' )
                {
                    $right = sha1 ( sha1 ( $right ) );
                }

                $value .= $a[0] . " = '" . $left . "' " . $cond . " " . $a[1] . " = '" . $right . "'";
            }
            else if ( count ( $a ) == 1 )
            {
                $left = $form[$a[0]];
                $value .= $a[0] . " = '" . $left . "' ";
            }
            else
            {
                print ( "invalid parameters" );
            }
        }

        return $value;
    }

    function Remove ( $file )
    {
        unlink ( $file );
    }

    function GetFileType ( $file, $type )
    {
        if ( $type == 'name' )
        {
            $file = explode ( '/', $file );
            $file = $file[1];
        }

        return $file;
    }

    function GetRealType ( $file, $type )
    {
        if ( $type == 'name' )
        {
            $file = explode ( '.', $file );
            $file = $file[count ( $file ) - 1];
        }

        return $file;
    }

    function FileCheck ( $files, $types )
    {
        $msg = array (
            "status" => 1,
            "msg" => "File passed all tests!"
        );
        
        if ( !in_array ( $files['type'], $types ) )
        {
            $msg = array (
                "status" => 0,
                "msg" => "Invalid file given!"
            );
        }
        else if ( $files['size'] > 2000000 )
        {
            $msg = array (
                "status" => 0,
                "msg" => "File size should be 2 MB or less!"
            );
        }

        return json_encode ( $msg );
    }

    function EmptyCheck ( $post, $leave )
    {
        $error = array (
            "status"    => 1,
            "msg"       => "Success"
        );
        
        foreach ( $post as $key => $value )
        {
            if ( !in_array ( $key, $leave ) )
            {
                if ( empty ( $value ) and !ctype_digit ( $value ) )
                {
                    if ( $value != 0 )
                    {
                        $error = array (
                            "status"    => 0,
                            "msg"       => "All fields are required!"
                        );
                        break;
                    }
                }
            }
        }
        
        return json_encode ( $error );
    }

    function CheckPassword ( $pass, $pass_len )
    {
        $error = array (
            "status"    => 1,
            "message"   => "Password passed the test"
        );

        if ( strlen ( $pass ) < $pass_len )
        {
            $error = array (
                "status"    => 0,
                "message"   => "Your password is too weak!"
            );
        }

        return $error;
    }

    function CheckEmail ( $email )
    {
        $error = array (
            "status"    => 1,
            "message"   => "Email passed the test"
        );

        if ( !filter_var ( $email, FILTER_VALIDATE_EMAIL ) )
        {
            $error = array (
                "status"    => 0,
                "message"   => "Invalid email address!"
            );
        }
        
        return $error;
    }

    function CheckPhone ( $phone )
    {
        $error = array (
            "status"    => 1,
            "message"   => "Phone passed the test"
        );

        if ( !ctype_digit ( $phone ) )
        {
            $error = array (
                "status"    => 0,
                "message"   => "Invalid phone number!"
            );
        }
        
        return $error;
    }

    function Filter ( $input, $conn )
    {
        // $rem = ['.', '_', '@', ' ', '"', "'", ',', '-', '+', '=', '*', '/', '%'];
        $str = $input;

        // for ( $i = 0; $i < strlen ( $input ); $i ++ )
        // {
        //     if ( !ctype_alpha ( $input[$i] ) and !ctype_digit ( $input[$i] ) )
        //     {
        //         if ( in_array ( $input[$i], $rem ) )
        //         {
        //             $str .= $input[$i];
        //         }
        //     }
        //     else
        //     {
        //         $str .= $input[$i];
        //     }
        // }

        $str = mysqli_real_escape_string ( $conn, $str );
        return $str;
    }

    function FilterAll ( $post, $conn )
    {
        foreach ( $post as $key => $value )
        {
            $post[$key] = Filter ( $value, $conn );
        }

        return $post;
    }

    function Connect ( $host, $user, $pass )
    {
        $conn = mysqli_connect ( $host, $user, $pass );

        return $conn;
    }

    function GetColumns ( $conn, $table, $leave )
    {
        $cols = [];
        $query = mysqli_query ( $conn, "SHOW COLUMNS FROM $table" );
        while ( $rr = mysqli_fetch_array ( $query ) )
        {
            if ( !in_array ( $rr['Field'], $leave ) )
            {
                $cols[] = $rr['Field'];
            }
        }

        return $cols;
    }

    function CreateValues ( $cols, $post, $leave )
    {
        $values = [];
        
        for ( $i = 0; $i < count ( $cols ); $i ++ )
        {
            if ( !in_array ( $cols[$i], $leave ) )
            {
                $values[] = $cols[$i] . " = '" . $post[$cols[$i]] . "'";
            }
        }
        return join ( ', ', $values );
    }
    
    function CreateInsert ( $post, $leave )
    {
        $cols = [];
        $vals = [];

        foreach ( $post as $key => $v )
        {
            if ( !in_array ( $key, $leave ) )
            {
                if ( $key == 'user' )
                {
                    $key = 's_id';
                }
                
                $cols[] = $key;
                $vals[] = "'$v'";
            }
        }

        return [$cols, $vals];
    }

    function GetImage ( $conn, $s_id )
    {
        $value  = "s_id = $s_id";
        $obj    = DB ( 2, 'photo', $value, "", $conn );
        $image  = $obj[1];
        if ( $image )
        {
            $obj    = DB ( 3, 'photo', $value, "", $conn );
            $image  = $obj[1];
            $image  = mysqli_fetch_array ( $image );
            $image  = $image['name'];
        }
        else
        {
            $image  = 'user.png';
        }

        return $image;
    }

    function DB ( $type, $table, $query, $msg, $conn, $cols )
    {
        $error = array (
            "status"    => 1,
            "msg"       => $msg
        );

        if ( $type == 1 )
        {
            $query = mysqli_query ( $conn, "INSERT INTO $table $query[0] VALUES $query[1]" );
            if ( !$query )
            {
                print ( mysqli_error ( $conn ) );
                exit ( );
            }
        }
        else if ( $type == 2 )
        {
            $query = mysqli_query ( $conn, "SELECT * FROM $table WHERE $query" );
            $error = [1, mysqli_num_rows ( $query )];
        }
        else if ( $type == 3 )
        {
            $query = mysqli_query ( $conn, "SELECT * FROM $table WHERE $query" );
            // print ( mysqli_error ( $conn ) );
            $error = [1, $query];
        }
        else if ( $type == 3.1 )
        {
            $query = mysqli_query ( $conn, "SELECT $cols FROM $table WHERE $query" );
            // print ( mysqli_error ( $conn ) );
            $error = [1, $query];
        }
        else if ( $type == 4 )
        {
            $query = mysqli_query ( $conn, "SELECT * FROM $table" );
            $error = [1, $query];
        }
        else if ( $type == 5 )
        {
            $query = mysqli_query ( $conn, "UPDATE $table SET $query[1] WHERE $query[0]" );
            // print ( mysqli_error ( $conn ) );
            $error = [1, $query];
        }
        else if ( $type == 6 )
        {
            $query = mysqli_query ( $conn, "DELETE FROM $table WHERE $query" );
            $error = [1, $query];
            // die ( mysqli_error ( $conn ) );
        }
        else if ( $type == 7 )
        {
            $query = mysqli_query ( $conn, "SELECT * FROM $table ORDER BY id DESC" );
            $error = [1, $query];
        }
        else if ( $type == 8 )
        {
            $query = mysqli_query ( $conn, "SELECT * FROM $table WHERE $query ORDER BY id DESC" );
            $error = [1, $query];
        }
        
        if ( $type != 3 and $type != 2 and $type != 4 and $type != 5 and $type != 6 and $type != 7 and $type != 8 and $type != 3.1 )
        {
            return json_encode ( $error );
        }
        else
        {
            return $error;
        }
    }

    function GetData ( $conn, $post )
    {
        $check  = EmptyCheck ( $post, ['session'] );
        $obj    = json_decode ( $check );
        if ( $obj -> status )
        {
            $form       = FilterAll ( $post, $conn );
            $table      = $form['table'];
            $session    = 0;
            
            if ( isset ( $form['create_session'] ) )
            {
                $session    = $form['create_session'];
            }

            $value  = Query ( $form );
            $data   = RemoveCred ( $table, $conn );
            $obj    = DB ( 3, $table, $value, "", $conn, "" ); 
            if ( mysqli_num_rows ( $obj[1] ) )
            {
                $data = mysqli_fetch_array ( $obj[1] );
                if ( $session )
                {
                    $msg = array (
                        "status" => 1,
                        "message" => "Successfull.. Please wait",
                        "data" => $data
                    );
                    $_SESSION['id'] = $data['id'];
                }
                else
                {
                    $msg = array (
                        "status" => 1,
                        "data" => $data
                    );
                }
            }
            else
            {
                if ( $session )
                {
                    $msg = array (
                        "status"    => 0,
                        "message"   => "Account does not exist!",
                    );
                }
                else
                {
                    $msg = array (
                        "status"    => 0,
                        "message"   => "No record was found",
                    );
                }
            }
        }
        else
        {
            $msg = array (
                "status"    => 0,
                "message"   => "All fields are required!",
            );
        }

        return $msg;
    }

    function GetDataAll ( $conn, $post )
    {
        $check  = EmptyCheck ( $post, ['session'] );
        $obj    = json_decode ( $check );
        if ( $obj -> status )
        {
            $form       = FilterAll ( $post, $conn );
            $table      = $form['table'];
            $session    = 0;
            
            if ( isset ( $form['create_session'] ) )
            {
                $session    = $form['create_session'];
            }

            $value  = Query ( $form );
            $data   = RemoveCred ( $table, $conn );
            $obj    = DB ( 3, $table, $value, "", $conn, "" ); 
            if ( mysqli_num_rows ( $obj[1] ) )
            {
                if ( $session )
                {
                    $data = mysqli_fetch_array ( $obj[1] );
                    $msg = array (
                        "status" => 1,
                        "message" => "Successfull.. Please wait",
                        "data" => $data
                    );
                    $_SESSION['id'] = $data['id'];
                }
                else
                {
                    $data = [];
                    while ( $x = mysqli_fetch_array ( $obj[1] ) )
                    {
                        // $data[] = json_encode ( $x );
                        $data[] = $x;
                    }
                    $msg = array (
                        "status" => 1,
                        "data" => $data
                    );
                }
            }
            else
            {
                if ( $session )
                {
                    $msg = array (
                        "status"    => 0,
                        "message"   => "Account does not exist!",
                    );
                }
                else
                {
                    $msg = array (
                        "status"    => 0,
                        "message"   => "Email address not found!",
                    );
                }
            }
        }
        else
        {
            $msg = array (
                "status"    => 0,
                "message"   => "All fields are required!",
            );
        }

        return $msg;
    }

    function PostData ( $conn, $post )
    {
        $check  = EmptyCheck ( $post, ['multiple_email', 'session'] );
        $obj    = json_decode ( $check );
        
        if ( $obj -> status )
        {
            $form       = FilterAll ( $post, $conn );
            $table      = $form['table'];
            $session    = 0;
            
            if ( isset ( $form['create_session'] ) )
            {
                $session    = $form['create_session'];
                if ( $form['check_credentials'] )
                {
                    $msg        = CheckCred ( $form['check_credentials'], $post );                    
                    // $obj    = json_encode ( $msg );
                    // $obj    = json_decode ( $obj );
                    if ( !$msg['status'] )
                    {
                        return $msg;
                    }
                }

                if ( $form['multiple_email'] == 0 )
                {
                    $msg        = EmailExists ( $form['email'], $conn, $table );
                    
                    $obj    = json_encode ( $msg );
                    $obj    = json_decode ( $obj );
                    
                    if ( !$obj -> status )
                    {
                        return $msg;
                    }
                }

                if ( $form['hash'] )
                {
                    $r = explode ( ',', $form['hash'] );
                    for ( $i = 0; $i < count ( $r ); $i += 1 )
                    {
                        $form[$i] = sha1 ( sha1 ( $form[$r[$i]] ) );
                    }
                }
            }
            
            $cols   = GetColumns ( $conn, $table, ['id'] );
            $query  = GetQuery ( $cols, $form );
            
            $obj    = DB ( 1, $table, [$query[0], $query[1]], "Success", $conn, "" );
            $obj    = json_decode ( $obj );
            if ( $obj -> status )
            {
                $msg = array (
                    "status"    => 1,
                    "message"   => "Operation was successful",
                );

                if ( $session )
                {
                    $value  = "email = '$form[email]'";
                    $obj    = DB ( 3, $table, $value, "", $conn, "" );
                    $data   = mysqli_fetch_array ( $obj[1] );
                    $_SESSION['id'] = $data['id'];
                    $msg = array (
                        "status" => 1,
                        "message" => "Account created successfully",
                        "data" => $data
                    );
                }
            }
        }
        else
        {
            $msg = array (
                "status"    => 0,
                "message"   => "All fields are required!",
            );
        }

        return $msg;
    }

    function UpdateData ( $conn, $post )
    {
        $check  = EmptyCheck ( $post, [] );
        $obj    = json_decode ( $check );
        if ( $obj -> status )
        {
            $form       = FilterAll ( $post, $conn );
            $params     = $form['params'];
            $table      = $form['table'];
            
            $params     = explode ( ",", $params );
            $value      = '';
            for ( $i = 0; $i < count ( $params ); $i += 1 )
            {
                if ( $params[$i] == 'password' )
                {
                    $pass = sha1 ( sha1 ( $post[$params[$i]] ) );
                }
                else
                {
                    $pass = $post[$params[$i]];
                }

                $value .= $params[$i] ." = '" . $pass ."'";
                if ( $i < count ( $params ) - 1 )
                {
                    $value .= ',';
                }
            }
            
            $query  = ['id = ' . $form['id'], $value];
            $obj    = DB ( 5, $table, $query, "", $conn, "" );
            if ( $obj[1] )
            {
                $msg = array (
                    "status"    => 1,
                    "message"   => "Table updated!",
                );
            }
        }
        else
        {
            $msg = array (
                "status"    => 0,
                "message"   => "All fields are required!",
            );
        }

        return json_encode ( $msg );
    }

    function UpdateDataWhere ( $conn, $post, $clause )
    {
        $check  = EmptyCheck ( $post, [] );
        $obj    = json_decode ( $check );
        if ( $obj -> status )
        {
            $form       = FilterAll ( $post, $conn );
            $params     = $form['params'];
            $table      = $form['table'];
            
            $params     = explode ( ",", $params );
            $value      = '';
            for ( $i = 0; $i < count ( $params ); $i += 1 )
            {
                if ( $params[$i] == 'password' )
                {
                    $pass = sha1 ( sha1 ( $post[$params[$i]] ) );
                }
                else
                {
                    $pass = $post[$params[$i]];
                }

                $value .= $params[$i] ." = '" . $pass ."'";
                if ( $i < count ( $params ) - 1 )
                {
                    $value .= ',';
                }
            }
            
            $query  = [$clause, $value];
            $obj    = DB ( 5, $table, $query, "", $conn, "" );
            if ( $obj[1] )
            {
                $msg = array (
                    "status"    => 1,
                    "message"   => "Table updated!",
                );
            }
        }
        else
        {
            $msg = array (
                "status"    => 0,
                "message"   => "All fields are required!",
            );
        }

        return json_encode ( $msg );
    }

    function GenPass ( )
    {
        return uniqid ( );
    }
    
    function FileProcessor ( $files, $names )
    {
        $xfile = [];
        for ( $x = 0; $x < count ( $names ); $x ++ )
        {
            if ( isset ( $files[$names[$x]] ) )
            {
                $file = $files[$names[$x]];
                $msg = FileCheck ( $file, ['image/jpeg', 'image/jpg', 'image/png'] );
                $msg = json_decode ( $msg );
                if ( $msg -> status == 0 )
                {
                    $msg = json_encode ( $msg );
                    return $msg;
                }
            }
        }

        for ( $x = 0; $x < count ( $names ); $x ++ )
        {
            if ( isset ( $files[$names[$x]] ) )
            {
                $file = $files[$names[$x]];
                $name = time ( ) . $file['name'];
                if ( move_uploaded_file ( $file['tmp_name'], '../static/teams/' . $name ) )
                {
                    $xfile[$names[$x]] = $name;
                }
            }

        }

        $msg = array (
            "status"    => 1,
            "message"   => "File uploaded",
            "data"      => $xfile
        );

        return json_encode ( $msg );
    }

    function ConfirmDay ( $date, $time )
    {
        $date = explode ( '-', $date );
        $msg = array (
            "status"    => 0,
            "message"   => "Invalid date given. Date should be in the future!"
        );
        
        if ( count ( $date ) == 3 )
        {
            $year = $date[0];
            $month = $date[1];
            $day = $date[2];
            
            $xyear = date ( 'Y', time ( ) );
            $xmonth = date ( 'm', time ( ) );
            $xday = date ( 'd', time ( ) );

            if ( $year >= $xyear and $month >= $xmonth )
            {
                $valid = 1;
                if ( $day < $xday and $month > $xmonth )
                {
                    $valid = 1;
                }
                else if ( $day == $xday and $month >= $xmonth or $day > $xday and $month >= $xmonth )
                {
                    $valid = 1;
                }
                else
                {
                    $valid = 0;
                }

                if ( $valid )
                {
                    $msg = array (
                        "status"    => 1,
                        "message"   => "Date is valid"
                    );
                }
            }
        }
        
        return $msg;
    }

    function CheckIfFileAdded ( $post, $files )
    {
        for ( $x = 0; $x < count ( $files ); $x ++ )
        {
            if ( !isset ( $_FILES[$files[$x]] ) )
            {
                $post[$files[$x]] = 'undefined';
            }
        }

        return $post;
    }

    function GetShort ( $name )
    {
        $short = '';
        $name = explode ( ' ', $name );
        if ( count ( $name ) < 2 )
        {
            for ( $i = 0; $i < count ( $name ); $i ++ )
            {
                $short .= $name[$i][0];
            }
        }
        else
        {
            for ( $i = 0; $i < count ( $name ); $i ++ )
            {
                if ( $name[$i] != 'and' )
                {
                    $short .= $name[$i][0];
                }
            }
        }

        return $short;
    }

    function GetLink ( $id, $title )
    {
        return "localhost/skoolbet/admin/fixture.php?" . sha1 ( sha1 ( $id ) ) . '&' . sha1 ( sha1 ( $title ) );
    }

    function Occurence ( $array, $item )
    {
        $cnt = 0;
        for ( $i = 0; $i < count ( $array ); $i ++ )
        {
            if ( $array[$i] == $item )
            {
                $cnt ++;
            }
        }

        return $cnt;
    }

    function GetScore ( $team, $a_score, $b_score )
    {
        if ( $team == 1 and $a_score > $b_score )
        {
            return 1;
        }
        else if ( $team == 2 and $b_score > $a_score )
        {
            return 1;
        }

        return 0;
    }

    function GetTeam ( $team, $teamx )
    {
        if ( $team == $teamx )
        {
            return 1;
        }
        else
        {
            return 2;
        }

        return 0;
    }

    function UpdateClient ( $conn, $bet )
    {
        $amount = $bet['amount'];
        $amount = (5 / 100) * $amount;

        $form = array (
            "sid"    => $_SESSION['id'],
            "table" => "wallet",
            "params"=> "sid"
        );
        $obj = GetData ( $conn, $form );

        $amount += $obj['data']['amount'];
        $form = array (
            "amount"    => $amount,
            "table"     => "wallet",
            "params"    => "amount",
            "id"        => $obj['data']['id']
        );
        $obj = UpdateData ( $conn, $form );

    }

    function Verify ( $account, $bank, $key )
    {
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/bank/resolve?account_number=$account&bank_code=$bank",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer $key",
            "Cache-Control: no-cache",
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            // echo $response;
        }

        return $response;
    }

    function Recipient ( $acc_name, $account, $bank, $key )
    {
        $url = "https://api.paystack.co/transferrecipient";
        $fields = [
            "type" => "nuban",
            "name" => $acc_name,
            "account_number" => $account,
            "bank_code" => $bank,
            "currency" => "NGN"
        ];
        $fields_string = http_build_query($fields);
        //open connection
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $key",
            "Cache-Control: no-cache",

        ));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        $result = curl_exec($ch);
        return $result;
    }

    function GenCode ( $cnt )
    {
        $code = '';
        for ( $i = 0; $i < $cnt; $i ++ )
        {
            $code .= rand ( 0, 50 );
        }

        return $code;
    }
    
    function Format ( $quest, $src, $course, $diff )
    {
        if ( $src == 'aloc' )
        {
            $quest = json_decode ( $quest );
            $section = $quest -> data -> section;
            $section = explode ( ',', $section );
            
            if ( count ( $section ) > 1 )
            {
                unset ( $section[0] );
            }
            
            $section = join ( ',', $section );
            $data = array (
                "id"        => $quest -> data -> id,
                "question"  => $section . '<br>' . $quest -> data -> question,
            );

            $ans = [];
            foreach ( $quest -> data -> option as $key => $value )
            {
                if ( empty ( $value) )
                {
                    $value = "null";
                }
                $ans['answer_' . $key] = $value;
            }
            $data['answers'] = $ans;
            $data['multiple_correct_answers'] = 'false';

            $ans = [];
            foreach ( $quest -> data -> option as $key => $value )
            {
                if ( $quest -> data -> answer == $key )
                {
                    $ans['answer_' . $key . '_correct'] = "true";
                }
                else
                {
                    $ans['answer_' . $key . '_correct'] = "false";
                }
            }
            $data['correct_answers'] = $ans;
            $data['correct_answer'] = $quest -> data -> answer;
            $data['tags'] = [["name" => $course]];
            $data['difficulty'] = $diff;
            $data['image'] = $quest -> data -> image;
            return json_encode ( $data );
        }
        
        return ( $quest );
    }
?>