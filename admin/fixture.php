<?php
    require ( "../backend/global.php" );
    $conn = Connect ( "localhost", "root", "" );
    mysqli_select_db ( $conn, 'skool' );

    $id = 0;
    $title = 0;
    $name = 0;
    $param = [];
    
    foreach ($_GET as $key => $value)
    {
        $param[] = $key;
    }

    if ( count ( $param ) == 1 )
    {
        $id = $param[0];
        $title = $param[0];
    }
    else
    {
        $id = $param[0];
        $title = $param[1];
    }

    $value  = "1";
    $user = DB ( 3, 'main_users', $value, "", $conn, "" );

    $value  = "1";
    $titles = DB ( 3, 'titles', $value, "", $conn, "" );

    while ( $x = mysqli_fetch_array ( $user[1] ) )
    {
        if ( sha1 ( sha1 ( $x['id'] ) ) == $id )
        {
            $id = $x['id'];
            break;
        }
    }

    while ( $x = mysqli_fetch_array ( $titles[1] ) )
    {
        if ( sha1 ( sha1 ( $x['id'] ) ) == $title )
        {
            $title = $x['id'];
            $name = $x['title'];
            break;
        }
    }
    $value  = "sid = $id";
    $games = DB ( 3, 'new_games', $value, "", $conn, "" );
?>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>
        <?php
            print ( $name );
        ?>
    </title>
    <!-- CSS files -->
    <link href="../dist/css/tabler.min.css?1692870487" rel="stylesheet" />
    <link href="../dist/css/tabler-flags.min.css?1692870487" rel="stylesheet" />
    <link href="../dist/css/tabler-payments.min.css?1692870487" rel="stylesheet" />
    <link href="../dist/css/tabler-vendors.min.css?1692870487" rel="stylesheet" />
    <link href="../dist/css/demo.min.css?1692870487" rel="stylesheet" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>
<body style="padding: 10px;">
    <span style="font-size: 12px;">
        All games fixtures for:
    </span>
    <h3 style="border-bottom: 1px solid #ccc">
        <?php
            print ( $name )
        ?>
    </H3>
    <?php
        $stage = [];
        while ( $x = mysqli_fetch_array ( $games[1] ) )
        {
            if ( $x['title'] == $title )
                $stage[] = $x['stage'];
        }

        $stage = array_unique ( $stage );
        sort ( $stage );
        mysqli_data_seek ( $games[1], 0 );

        for ( $m = 0; $m < count ( $stage ); $m ++ )
        {
            $value  = "id = $stage[$m]";
            $stages = DB ( 3, 'stages', $value, "", $conn, "" );
            $stages = mysqli_fetch_array ( $stages[1] );
            print (
                "<div align='center' style='margin-bottom: 10px;'>
                    <button class='btn btn-sm btn-primary'>$stages[title]</button>
                </div>"
            );
    ?>
            <table class="table table-vcenter">
                <thead>
                    <tr align="center">
                        <th>Teams</th>
                        <th>Score</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $value  = "sid = $id and title = $title";
                    $games = DB ( 3, 'new_games', $value, "", $conn, "" );
                    while ( $x = mysqli_fetch_array ( $games[1] ) )
                    {
                        $date = strtolower ( $x['game_date'] );
                        $today = strtolower ( date ( "M d, Y", time ( ) ) );
                        
                        if ( $x['stage'] == $stage[$m] )
                        {
                ?>
                            <tr align="center">
                                <td align="center">
                                    <div style="margin-bottom: 5px; font-size: 14px;text-transform:capitalize">
                                        <?php
                                            print ( $x['team_a'] );
                                        ?>
                                    </div>
                                    <div>
                                        <small class="text-secondary" style="text-transform: capitalize;font-size: 11px">
                                            <?php print ( $x['game_date'] ) ?> | <?php print ( $x['game_time'] ) ?>
                                        </small>
                                    </div>
                                    <div style="margin-top: 5px; font-size: 14px;text-transform:capitalize">
                                        <?php
                                            print ( $x['team_b'] )
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                        print ( $x['a_score'] )
                                    ?> : <?php
                                    print ( $x['b_score'] )
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        if ( $x['status'] == 1 )
                                        {
                                            print (
                                                '<span class="status-dot status-dot-animated bg-red d-block" style="width: 15px; height: 15px;"></span>
                                                <span style="font-size: 12px;">
                                                    Playing
                                                </span>'
                                            );
                                        }
                                        else if ( $x['status'] == 0 )
                                        {
                                            print (
                                                '<span class="badge bg-red-lt">Still Pending</span>'
                                            );
                                        }
                                    ?>
                                </td>
                            </tr>
                <?php
                        }
                    }
                ?>
                </tbody>
            </table>
    <?php
        }
    ?>
    <footer class="footer footer-transparent d-print-none">
        <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
                <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                    <ul class="list-inline list-inline-dots mb-0">
                        <li class="list-inline-item">
                            Copyright &copy; <?php
                                print ( date ( 'Y', time ( ) ) );
                            ?>
                            <a href="./" class="link-secondary">Skoolbet</a>.
                            All rights reserved.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
