<?php
    session_start ( );
    // date_default_timezone_set('Africa/Lagos');
    require "global.php";
    require "mail.php";
    
    $KEY = "sk_live_d61193319f150feb40d7660a3b050a8e798d4a5b";
    $QUIZ_API = "YTsBhF1xzg4yF40GrzixiclXx6xALZmjWc63kIxS";
    $EMAIL = "skoolbet@gmail.com";
    $conn = Database ( );
    
    if ( isset ( $_POST['req'] ) )
    {
        if ( $_POST['req'] == 'get' )
        {
            $post = FilterAll ( $_POST, $conn );
            $msg = GetData ( $conn, $_POST );
        }
        else if ( $_POST['req'] == 'post' )
        {
            $post = FilterAll ( $_POST, $conn );
            $msg = PostData ( $conn, $post );
            
            if ( $msg['status'] )
            {
                $form = array (
                    "email" => $post['email'],
                    "table" => "users",
                    "params" => "email"
                );

                $obj    = GetData ( $conn, $form );
                $id     = $obj['data']['id'];

                $cols   = '( sid )';
                $value  = "( $id )";
                $obj    = DB ( 1, 'wallet', [$cols, $value], "Wallet created", $conn, "" );
            }
        }
        else if ( $_POST['req'] == 'update' )
        {
            $post = FilterAll ( $_POST, $conn );
            $msg = UpdateData ( $conn, $post );
        }
        else if ( $_POST['req'] == 'reset' )
        {
            $post = FilterAll ( $_POST, $conn );
            $msg = GetData ( $conn, $post );
            $obj = json_encode ( $msg );
            $obj = json_decode ( $obj );
            if ( $obj -> status == 1 )
            {
                $email = $obj -> data -> email;
                $pass = GenPass ( );
                $post = array (
                    "table"     => "users",
                    "password"  => $pass,
                    "id"        => $obj -> data -> id,
                    "params"    => "password"
                );
                
                $obj = UpdateData ( $conn, $post );
                $obj = json_decode ( $obj );
                if ( $obj -> status )
                {
                    $msg = Send ( $email, "SkoolBet", "New password created!", MailBody ( 'forgot', $pass ) );
                }
                else
                {
                    $msg = array (
                        "status"    => 0,
                        "message"   => "Server error occurred, please try again!",
                    );
                }
            }
        }
        else if ( $_POST['req'] == 'game' )
        {
            $_POST['sid'] = $_SESSION['id'];
            $post = FilterAll ( $_POST, $conn );
            $common = uniqid ( );

            $amt    = $post['amount'];
            $can_run= 0;
            if ( ctype_digit ( $amt ) )
            {
                if ( $amt < 50 )
                {
                    $msg = array (
                        "status" => 0,
                        "message" => "The minimum amount is 50 naira"
                    );
                }
                else
                {
                    $can_run = 1;
                }
            }
            else
            {
                $msg = array (
                    "status" => 0,
                    "message" => "No amount given"
                );
            }
            
            if ( $can_run )
            {
                $form = array (
                    "sid"       => $post['sid'],
                    "table"     => "wallet",
                    "params"    => "sid"
                );
                
                $obj = GetData ( $conn, $form );
                $obj = json_encode ( $obj );
                $obj = json_decode ( $obj );
                $main_amt = $obj -> data -> amount;
                $row_id = $obj -> data -> id;
                if ( $main_amt < $amt )
                {
                    $can_run = 0;
                    $msg = array (
                        "status" => 0,
                        "message" => "You dont have sufficient balance"
                    );
                }
                else
                {
                    $new = $main_amt - $amt;
                    $form = array (
                        "id"       => $row_id,
                        "table"     => "wallet",
                        "amount"    => $new,
                        "params"    => "amount"
                    );
                    $obj = UpdateData ( $conn, $form );
                }
            }
            
            if ( $can_run )
            {
                $param = explode ( '|', $post['options_bet'] );
                $teams = explode ( '|', $post['bet_team'] );
                for ( $i = 0; $i < count ( $param ); $i += 1 )
                {
                    $e = explode ( ',', $param[$i] );
                    if ( count ( $e ) == 5 )
                    {
                        if ( $i < count ( $teams ) )
                        {
                            $team = $teams[$i];
                        }
                        else if ( $teams[$i] == '' )
                        {
                            $team = 0;
                        }

                        $form = array(
                            "sid"       => $post['sid'],
                            "amount"    => $post['amount'],
                            "slip_no"   => $e[0],
                            "gid"       => $e[2],
                            "bet"       => $e[3],
                            "odd"       => $e[4],
                            "common"    => $common,
                            "status"    => -1,
                            "table"     => $post['table'],
                            "team"      => $team
                        );
                        // print_r ( $form );
                        $msg = PostData ( $conn, $form );
                    }
                }
            }
        }
        else if ( $_POST['req'] == 'wallet' )
        {
            $_POST['sid']   = $_SESSION['id'];
            $amt            = Filter ( $_POST['amount'], $conn );

            $form = array (
                "sid"    => $_POST['sid'],
                "table"    => "wallet",
                "params"    => "sid"
            );

            $form = GetData ( $conn, $form );
            $form = json_encode ( $form );
            $form = json_decode ( $form );

            $amount = $_POST['amount'] + $form -> data -> amount;
            $sid    = $_SESSION['id'];

            $form = array (
                "id"    => $form -> data -> id,
                "table"    => "wallet",
                "amount"    => $amount,
                "params"    => "amount"
            );
            $obj = UpdateData ( $conn, $form );

            $form = array (
                "sid"       => $sid,
                "table"     => "payment",
                "amount"    => $_POST['amount'],
                "params"    => "amount",
                "response"    => $_POST['response']
            );
            $obj = PostData ( $conn, $form );

            $msg = array (
                "status"    => 1,
                "message"   => "Operation was successfull!",
            );
        }
        else if ( $_POST['req'] == 'withdraw' )
        {
            $post = FilterAll ( $_POST, $conn );
            if ( $post['amount'] != 0 )
            {
                $form = array (
                    "sid"   => $_SESSION['id'],
                    "params" => "sid",
                    "table"     => "wallet"
                );
                $obj = GetData ( $conn, $form );
                if ( $obj['data']['amount'] > $post['amount'] )
                {
                    $rep = Recipient ( $post['acct_valid'] , $_POST['account'], $_POST['banks'], $KEY );
                    $rep = json_decode ( $rep );
                    $rep_code = '';
                    $ref = GenCode ( 16 );
                    
                    $form = array (
                        "sid"   => $_SESSION['id'],
                        "params" => "sid",
                        "table"     => "beneficiaries"
                    );

                    $obj = GetData ( $conn, $form );
                    $rep_code = $rep -> data -> recipient_code;
                    $rep = json_encode ( $rep );
                    $form = array (
                        "sid"       => $_SESSION['id'],
                        "response"  => $rep,
                        "table"     => "beneficiaries"
                    );

                    if ( !$obj['status'] )
                    {
                        $obj = PostData ( $conn, $form );
                        $obj = json_encode ( $obj );
                    }
                    else
                    {
                        $form['params'] = 'response';
                        $form['id']     = $obj['data']['id'];
                        $msg = UpdateData ( $conn, $form );
                        $rep = $obj['data']['response'];
                        $rep = json_decode ( $rep );
                        $rep_code = $rep -> data -> recipient_code;
                    }

                    $form = array (
                        "sid"       => $_SESSION['id'],
                        "amount"    => $post['amount'],
                        "table"     => "withdraw",
                        "status"    => -1,
                        "admin"    => 1,
                    );
                    $obj = PostData ( $conn, $form );

                    $form = array (
                        "sid"   => $_SESSION['id'],
                        "params" => "sid",
                        "table"     => "wallet"
                    );
                    $obj = GetData ( $conn, $form );

                    $amount = $obj['data']['amount'];
                    $amount -= $post['amount'];
                    $form = array (
                        "sid"       => $_SESSION['id'],
                        "amount"    => $amount,
                        "table"     => "wallet",
                        "params"    => "amount",
                        "id"        => $obj['data']['id']
                    );
                    $msg = UpdateData ( $conn, $form );

                    $msg = array (
                        "status"    => 1,
                        "message"   => "Transaction was successfull. Account will be credited in less than 45 minutes",
                    );
                    // $obj = Transfer ( $post['amount'], $ref, $rep_code, "SKOOL BET", $KEY );
                    
                    $mail = Send ( $EMAIL, "SkoolBet", "Fund Request!", MailBody ( 'withdraw', "<a href='https://skoolbet.com.ng/send/'>Send Money</a>" ) );
                }
                else
                {
                    $msg = array (
                        "status"    => 0,
                        "message"   => "Insufficient balance",
                    );
                }
            }
            else
            {
                $msg = array (
                    "status"    => 0,
                    "message"   => "Amount should not be zero",
                );
            }
        }
        else if ( $_POST['req'] == 'verify' )
        {
            $obj = Verify ( $_POST['account'], $_POST['bank'], $KEY );
            $obj = json_decode ( $obj );
            if ( $obj -> status == 0 )
            {
                $msg = array (
                    "status"    => 0,
                    "message"   => "Could not verify account details. Check your input and try again",
                );
            }
            else
            {
                $msg = array (
                    "status"    => 1,
                    "message"   => $obj -> data -> account_name,
                );
            }
        }
        else if ( $_POST['req'] == 'profile' or $_POST['req'] == 'admin_profile' )
        {
            $form = FilterAll ( $_POST, $conn );
            $form['id'] = $_SESSION['id'];
            
            if ( $_POST['req'] == 'admin_profile' )
            {
                $form['table'] = 'main_users';
            }
            else
            {
                $form['table'] = 'users';
            }
            
            $form['params'] = 'name,email,phone,password';
            
            if ( $form['password'] == $form['user_new_pass'] )
            {
                $msg = array (
                    "status"    => 0,
                    "message"   => "New password should not be the same as the old",
                );
            }
            else
            {
                $obj = CheckPassword ( $form['user_new_pass'], 8 );
                if ( $obj['status'] == 1 )
                {
                    // print_r ( sha1 ( sha1 ( $form['user_new_pass'] ) ) );
                    // exit ( 1 );
                    $form['password'] = $form['user_new_pass'];
                    $msg = UpdateData ( $conn, $form );
                    $msg = array (
                        "status"    => 1,
                        "message"   => "Update successfull",
                    );
                }
                else
                {
                    $msg = array (
                        "status"    => 0,
                        "message"   => $obj -> message,
                    );
                }
            }
        }
        else if ( $_POST['req'] == 'quiz' )
        {
            $game_count = 10;
            $post = FilterAll ( $_POST, $conn );
            $amount = $post['amount'];
            $post['sid'] = $_SESSION['id'];
            $id = $_SESSION['id'];

            $form = array (
                "sid"    => $id,
                "table"    => "wallet",
                "params"    => "sid"
            );
            $wallet = GetData ( $conn, $form );

            $form = array (
                "sid"    => $id,
                "table"    => "playing",
                "params"    => "sid"
            );
            $form = GetData ( $conn, $form );

            if ( $form['status'] )
            {
                $value  = "sid = $id";
                $del = DB ( 6, 'playing', $value, "", $conn, "" );
            }
            
            if ( $wallet['data']['amount'] > $amount )
            {
                if ( $amount >= 50 )
                {
                    $form = array (
                        "sid"       => $id,
                        "amount"    => $wallet['data']['amount'] - $amount,
                        "params"    => "amount",
                        "id"        => $wallet['data']['id'],
                        "table"     => "wallet"
                    );
                    $msg = UpdateData ( $conn, $form );

                    $form = array (
                        "sid"       => $id,
                        "table"     => "playing",
                        "data"      => json_encode ( $post )
                    );
                    $obj = PostData ( $conn, $form );

                    $form = array (
                        "table"    => "levels",
                        "level"    => $post['level'],
                        "params"   => "level"
                    );
                    $level = GetData ( $conn, $form );

                    $course = $post['course'];
                    $course = explode ( ',', $course );
                    $per_game = $game_count / count ( $course );
                    $rem = $game_count % count ( $course );
                    
                    $collect = [];
                    for ( $x = 0; $x < count ( $course ); $x += 1 )
                    {
                        $collect[] = (int) $per_game;
                    }

                    $cnt = 0;
                    for ( $i = 0; $i < $rem; $i += 1 )
                    {
                        if ( $cnt < count ( $course ) )
                        {
                            $collect[$cnt] += 1;
                            $cnt ++;
                        }
                        else
                        {
                            $cnt = 0;
                        }
                    }

                    $main_question = [];
                    for ( $i = 0; $i < count ( $course ); $i += 1 )
                    {
                        $form = array (
                            "table"    => "courses",
                            "short"    => $course[$i],
                            "params"   => "short"
                        );
                        $data = GetData ( $conn, $form );
                        
                        $name = $data['data']['name'];
                        $value  = "course = '$name' ORDER BY RAND () LIMIT $collect[$i]";
                        $quest = DB ( 3, 'questions', $value, "", $conn, "" );
                        
                        while ( $x = mysqli_fetch_array ( $quest[1] ) )
                        {
                            $e = json_decode ( $x['question'] );
                            if ( $e -> multiple_correct_answers == 'false' )
                            {
                                $x['question'] = html_entity_decode ( $x['question'] );
                                $main_question[] = $x;
                            }
                        }
                    }
                    // print_r ( $level );
                    $data = array (
                        "course"    => $post['course'],
                        "level"     => $level['data']['level'],
                        "time"      => $level['data']['time'],
                        "questions" => $main_question
                    );

                    $msg = array (
                        "status"    => 1,
                        "message"   => "Game as started!",
                        "data"      => $data
                    );
                }
                else
                {
                    $msg = array (
                        "status"    => 0,
                        "message"   => "The minimum amount you can play is 50 naira"
                    );
                }
            }
            else
            {
                $msg = array (
                    "status"    => 0,
                    "message"   => "Insufficient fund"
                );
            }
        }
        else if ( $_POST['req'] == 'submit' )
        {
            $post = FilterAll ( $_POST, $conn );
            $opt = explode ( '-', $post['options'] );

            $form = array (
                "sid"   => $_SESSION['id'],
                "table" => "playing",
                "params"=> "sid"
            );

            $obj = GetData ( $conn, $form );
            $obj = json_decode ( $obj['data']['data'] );
            $sel = explode ( ',', $obj -> course );

            $level = $obj -> level;
            $amount = $obj -> amount;

            $form = array (
                "level"   => $level,
                "table" => "levels",
                "params"=> "level"
            );
            $obj = GetData ( $conn, $form );
            $obj = $obj['data'];
            $odds = $obj['odds'];
            $correct = 0;
            
            for ( $i = 0; $i < count ( $opt ); $i ++ )
            {
                $a = explode ( ',', $opt[$i] );
                $form = array (
                    "id"   => $a[3],
                    "table" => "questions",
                    "params"=> "id"
                );
                $obj = GetData ( $conn, $form );
                $id = $obj['data']['id'];
                $obj = json_decode ( $obj['data']['question'] );
                if ( $a[3] == $id )
                {
                    $r = explode ( '_', $obj -> correct_answer );
                    if ( count ( $r ) == 1 )
                    {
                        $r = 'answer_' . $obj -> correct_answer;
                    }
                    else
                    {
                        $r = join ( '_', $r );
                    }
                    
                    if ( $a[0] == $r )
                    {
                        $correct ++;
                    }
                }
            }
            
            $per = ($correct / count ( $opt )) * 100;
            $per = round ( $per, 1 );
            if ( $per > 100 )
            {
                $per = 100;
            }
            if ( $per >= $level )
            {
                $amount *= $odds;
                if ( $per == $level )
                {
                    $message = "Congratulations!!! You scored $per%. Your account has been credited with &#8358 $amount";
                }
                else
                {
                    $message = "Congratulations!!! You scored $per% but you bet on $level%. Your account has been credited with &#8358 $amount";
                }

                $form = array (
                    "sid"   => $_SESSION['id'],
                    "table" => "wallet",
                    "params"=> "sid"
                );
    
                $obj = GetData ( $conn, $form );
                $main = $obj['data']['amount'];
                $main += $amount;
                
                $form = array (
                    "id"   => $obj['data']['id'],
                    "table" => "wallet",
                    "amount" => $main,
                    "params"=> "amount"
                );
                $obj = UpdateData ( $conn, $form );

                $msg = array (
                    "status"    => 1,
                    "message"   => $message
                );
            }
            else
            {
                $msg = array (
                    "status"    => 0,
                    "message"   => "You did not meet up with the percentage you set. <b style='color: red'>You scored $per% but you bet for $level%</b>"
                );
            }
        }
        else if ( $_POST['req'] == 'new_games' )
        {
            $data = $_POST['arr'];
            $title = '';
            $custom_game = '';
            $game_stage = '';
            for ( $i = 0; $i < count ( $data ); $i ++ )
            {
                $t = json_decode ( $data[$i] );
                $keys = array_keys ( get_object_vars ( $t ) );
                if ( count ( $keys ) != 0 )
                {
                    $post = [];
                    for ( $x = 0; $x < count ( $keys ); $x ++ )
                    {
                        if ( $keys[$x] == 'game_title' )
                        {
                            $title = $t -> $keys[$x];
                        }
                        else if ( $keys[$x] == 'game_stage' )
                        {
                            $game_stage = $t -> $keys[$x];
                        }
                        else if ( $keys[$x] == 'custom_game' )
                        {
                            $custom_game = $t -> $keys[$x];
                        }
                        else
                        {
                            $post[$keys[$x]] = $t -> $keys[$x];
                        }
                    }
                    
                    $post = FilterAll ( $post, $conn );
                    $post['sid'] = $_SESSION['id'];
                    $post['title'] = $title;
                    $post['stage'] = $game_stage;
                    $post['table'] = "new_games";
                    $post['a_score'] = "0";
                    $post['b_score'] = "0";
                    $post['winner'] = "NULL";
                    $post['status'] = "0";
                    $post['season'] = "0";
                    $post['extra_time'] = "0";
                    $post['a_penalty'] = "0";
                    $post['b_penalty'] = "0";
                    $post['school'] = $_SESSION['school'];

                    $can_write = 1;
                    if ( $title == 'sk_custom' )
                    {
                        $form = array (
                            "title"     => $custom_game,
                            "table"     => "titles",
                            "params"     => "title",
                        );
                        $obj = GetData ( $conn, $form );
                        if ( !$obj['status'] )
                        {
                            $form = array (
                                "title"     => $custom_game,
                                "table"     => "titles",
                            );
                            $msg = PostData ( $conn, $form );
                            $form = array (
                                "title"     => $custom_game,
                                "table"     => "titles",
                                "params"     => "title",
                            );
                            $obj = GetData ( $conn, $form );
                            $post['title'] = $obj['data']['id'];
                        }
                        else
                        {
                            $msg = array (
                                "status"    => 0,
                                "message"   => "Game title already exists"
                            );
                            $can_write = 0;
                        }
                    }

                    if ( $can_write )
                    {
                        $post = CheckIfFileAdded ( $post, ['a_logo', 'b_logo'] );
                        
                        $msg = ConfirmDay ( $post['game_date'], 'future' );
                        if ( $msg['status'] )
                        {
                            if ( isset ( $_FILES ) )
                            {
                                if ( count ( $_FILES ) )
                                {
                                    $msg = FileProcessor ( $_FILES, ['a_logo', 'b_logo'] );
                                    $msg = json_decode ( $msg );
                                    if ( $msg -> status == 0 )
                                    {
                                        $can_write = 0;
                                    }
                                    else
                                    {
                                        $can_write = 1;
                                        $keys = array_keys ( ( get_object_vars ( $msg -> data ) ) );
                                        for ( $x = 0; $x < count ( $keys ); $x ++ )
                                        {
                                            $post[$keys[$x]] = $msg -> data -> $keys[$x];
                                        }
                                    }
                                }
                            }

                            if ( $can_write )
                            {
                                $msg = PostData ( $conn, $post );
                                if ( $msg['status'] == 0 )
                                {
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
        else if ( $_POST['req'] == 'update_game' )
        {
            $post = $_POST;
            $post = FilterAll ( $post, $conn );
            $post['sid'] = $_SESSION['id'];
            $msg = ConfirmDay ( $post['game_date'], 'future' );
            $can_write = 1;
            if ( $msg['status'] )
            {
                if ( isset ( $_FILES ) )
                {
                    if ( count ( $_FILES ) )
                    {
                        $msg = FileProcessor ( $_FILES, ['a_logo', 'b_logo'] );
                        $msg = json_decode ( $msg );
                        if ( $msg -> status == 0 )
                        {
                            $can_write = 0;
                        }
                        else
                        {
                            $can_write = 1;
                            $keys = array_keys ( ( get_object_vars ( $msg -> data ) ) );
                            for ( $x = 0; $x < count ( $keys ); $x ++ )
                            {
                                $post[$keys[$x]] = $msg -> data -> $keys[$x];
                            }
                        }
                    }
                }
            }
            
            if ( $can_write )
            {
                $post['params'] = "title,stage,team_a,team_b,a_odds,b_odds,game_date,game_time,a_logo,b_logo";
                $msg = UpdateData ( $conn, $post );
                $msg = array (
                    "status"    => 1,
                    "message"   => "Process completed"
                );
            }
        }
        else if ( $_POST['req'] == 'close_game' )
        {
            $post = FilterAll ( $_POST, $conn );
            if ( $post['season'] == 0 )
            {
                $season = 1;
                $m = "Game closed";
            }
            else
            {
                $season = 0;
                $m = "Game opened";
            }

            $query  = ['title = ' . $post['title'] . ' and sid = ' . $_SESSION['id'] . ' and school = ' . $_SESSION['school'], "season = " . $season];
            $obj    = DB ( 5, "new_games", $query, "", $conn, "" );
            
            $msg = array (
                "status"    => 1,
                "message"   => $m
            );
        }
        else if ( $_POST['req'] == 'kickoff' )
        {
            $post = FilterAll ( $_POST, $conn );
            $form = array (
                "id"    => $post['id'],
                "status" => 1,
                "params"=> "status",
                "table" => "new_games",
            );

            $obj = UpdateData ( $conn, $form );
            $obj = json_decode ( $obj );
            $msg = array (
                "status"    => 1,
                "message"   => "Game as started",
                "data"      => $post['id']
            );
        }
        else if ( $_POST['req'] == 'final_whistle' )
        {
            $post = FilterAll ( $_POST, $conn );
            $valid = 0;

            if ( $post['score_a'] != 0 and $post['score_b'] != 0 )
            {
                if ( empty ( $post['score_a'] ) or empty ( $post['score_b'] ) )
                {
                    $valid = 1;
                }
            }

            if ( $valid )
            {
                {
                    $msg = array (
                        "status"    => 0,
                        "message"   => "The score for both teams was not given!"
                    );
                }
            }
            else
            {
                $form = array (
                    "id"    => $post['id'],
                    "table" => "new_games",
                    "params"=> "id"
                );
                
                $game = GetData ( $conn, $form );
                $game = $game['data'];
                
                $form = array ( );
                $form['sid'] = $_SESSION['id'];
                $form['a_score'] = $post['score_a'];
                $form['b_score'] = $post['score_b'];
                $form['extra_time'] = $post['extra_time_' . $post['id']];
                $form['a_penalty'] = $post['score_a_pen'];
                $form['b_penalty'] = $post['score_b_pen'];
                $form['id'] = $post['id'];
                $form['params'] = "a_score,b_score,extra_time,a_penalty,b_penalty,winner";
                $form['table'] = "new_games";
                
                $winner = '';
                $a_score = $post['score_a'];
                $b_score = $post['score_b'];
                $ext_time = $form['extra_time'];
                $pen_a = $post['score_a_pen'];
                $pen_b = $post['score_b_pen'];

                if ( $a_score > $b_score )
                {
                    $winner = $game['team_a'];
                }
                else if ( $b_score > $a_score )
                {
                    $winner = $game['team_b'];
                }
                else
                {
                    $winner = "Draw";
                    if ( $post['score_a_pen'] != 0 and $post['score_b_pen'] != 0 )
                    {
                        $a_score = $post['score_a_pen'];
                        $b_score = $post['score_b_pen'];
                        if ( $a_score > $b_score )
                        {
                            $winner = $game['team_a'];
                        }
                        else if ( $b_score > $a_score )
                        {
                            $winner = $game['team_b'];
                        }
                    }
                }

                $form['winner'] = $winner;
                $obj = UpdateData ( $conn, $form );
                
                $form = array (
                    "gid"    => $post['id'],
                    "table" => "games",
                    "params"=> "gid"
                );
                $obj = GetData ( $conn, $form );
                
                if ( $obj['status'] != 0 )
                {
                    $obj = $obj['data']['common'];
                    $form = array (
                        "common"    => $obj,
                        "table" => "games",
                        "params"=> "common"
                    );
                    $obj = GetDataAll ( $conn, $form );
                    $main_data = $obj['data'];

                    $status = [];
                    $bet = 0;
                    for ( $i = 0; $i < count ( $main_data ); $i ++ )
                    {
                        $data = $main_data[$i];
                        $status[] = $data['status'];
                        if ( $data['gid'] == $post['id'] )
                        {
                            $bet = $data;
                        }
                    }
                    
                    $msg = array (
                        "status"    => 1,
                        "message"   => "Game closed"
                    );
                    $cnt = Occurence ( $status, -1 );
                    
                    if ( 1 )
                    {
                        if ( $bet['bet'] == 1 )
                        {
                            if ( $ext_time != 1 )
                            {
                                $type = 2;
                                $win = 0;
                            }
                            else
                            {
                                $win = 0;
                                $team = GetTeam ( $bet['team'], $game['team_a'] );
                                
                                if ( $a_score != $b_score )
                                {
                                    $win = GetScore ( $team, $a_score, $b_score );
                                }
                                else
                                {
                                    $win = GetScore ( $team, $pen_a, $pen_b );
                                }

                                if ( $win )
                                {
                                    $type = 1;
                                }
                                else
                                {
                                    $type = 2;
                                }
                            }
                        }
                        else if ( $bet['bet'] == 2 )
                        {
                            if ( $ext_time == 0 )
                            {
                                $team = GetTeam ( $bet['team'], $game['team_a'] );
                                $win = GetScore ( $team, $a_score, $b_score );
                                if ( $win )
                                {
                                    $type = 1;
                                }
                                else
                                {
                                    $type = 2;
                                }
                            }
                            else
                            {
                                $type = 2;
                            }
                        }
                        else if ( $bet['bet'] == 3 )
                        {
                            if ( $a_score != 0 and $b_score != 0 )
                            {
                                $type = 1;
                                $win = 1;
                            }
                            else
                            {
                                $type = 2;
                                $win = 0;
                            }
                        }
                        else if ( $bet['bet'] == 4 )
                        {
                            if ( $a_score == $b_score )
                            {
                                $type = 1;
                                $win = 1;
                            }
                            else
                            {
                                $type = 2;
                                $win = 0;
                            }
                        }

                        $form = array (
                            "status"    => $type,
                            "table"     => "games",
                            "params"    => "status",
                            "id"        => $bet['id']
                        );
                        $obj = UpdateData ( $conn, $form );
                    }
                    
                    if ( $cnt == 1 and Occurence ( $status, 2 ) == 0 )
                    {
                        if ( $win )
                        {
                            $cnt = Occurence ( $status, 2 );
                            if ( !$cnt )
                            {
                                $odds = [];
                                $amount = 0;
                                $sid = 0;
                                for ( $i = 0; $i < count ( $main_data ); $i ++ )
                                {
                                    $data = $main_data[$i];
                                    $odds[] = $data['odd'];
                                    $amount = $data['amount'];
                                    $sid = $data['sid'];
                                }

                                $form = array (
                                    "sid"       => $sid,
                                    "table"     => "wallet",
                                    "params"    => "sid"
                                );
                                
                                $obj = GetData ( $conn, $form );

                                $odds = array_sum ( $odds );
                                $amount *= $odds;
                                $total_amount = $obj['data']['amount'] + $amount;

                                $form = array (
                                    "id"       => $obj['data']['id'],
                                    "table"     => "wallet",
                                    "amount"    => $total_amount,
                                    "params"    => "amount"
                                );
                                $obj = UpdateData ( $conn, $form );
                                
                            }
                        }
                        else
                        {
                            $form = array (
                                "status"    => 2,
                                "table"     => "games",
                                "params"    => "status"
                            );
                            $obj = UpdateDataWhere ( $conn, $form, "common = '$bet[common]'" );
                            UpdateClient ( $conn, $bet );
                        }
                    }
                    else if ( $type == 2 or Occurence ( $status, 2 ) != 0 )
                    {
                        $form = array (
                            "status"    => 2,
                            "table"     => "games",
                            "params"    => "status"
                        );
                        $obj = UpdateDataWhere ( $conn, $form, "common = '$bet[common]'" );
                        UpdateClient ( $conn, $bet );
                    }
                }
                else
                {
                    $msg = array (
                        "status"    => 1,
                        "message"   => "Game closed",
                    );
                }
            }
            $form = array (
                "status"    => '2',
                "table"     => "new_games",
                "params"    => "status",
                "id"        => $post['id']
            );
            $obj = UpdateData ( $conn, $form );
        }
        else
        {
            $msg = array (
                "status"    => 0,
                "message"   => "Server cannot process request",
            );
        }
    }
    else
    {
        $msg = array (
            "status"    => 0,
            "message"   => "Server cannot process request",
        );
    }

    $obj = json_encode ( $msg );
    print ( $obj );
?>