<?php
    session_start ( );
    date_default_timezone_set('Africa/Lagos');
    if ( isset ( $_GET['logout'] ) )
    {
        unset ( $_SESSION );
    }
    
    if ( !isset ( $_SESSION['id'] ) )
    {
        print (
          '<meta http-equiv="refresh" content="0;../admin"></meta>'
        );
        exit ( 1 );
    }
    require ( "../backend/global.php" );

    $main_id = $_SESSION['id'];
    $conn = Database ( );

    $obj    = DB ( 4, 'schools', "", "", $conn, '' );
    $skool = $obj[1];

    $value  = "id = $main_id";
    $bio    = DB ( 3, 'main_users', $value, "", $conn, "" );
    $bio    = mysqli_fetch_array ( $bio[1] );
    $_SESSION['school'] = $bio['school'];
    
    $value  = "sid = $main_id and admin = 1";
    $wallet    = DB ( 3, 'wallet', $value, "", $conn, "" );
    $wallet    = mysqli_fetch_array ( $wallet[1] );

    $value  = "sid = $main_id and admin = 1";
    $collect = DB ( 3, 'withdraw', $value, "", $conn, "" );

    $value  = "sid = $main_id and status != 2";
    $my_games = DB ( 3, 'new_games', $value, "", $conn, "" );

    $value  = "sid = $main_id and status = 2";
    $played_games = DB ( 3, 'new_games', $value, "", $conn, "" );

    $value  = "sid = $main_id";
    $all_games = DB ( 3, 'new_games', $value, "", $conn, "" );
    
    $value  = "1";
    $season = DB ( 3, 'titles', $value, "", $conn, "" );
    $xseason = [];
    while ( $x = mysqli_fetch_array ( $season[1] ) )
    {
        $xseason[] = $x['id'];
    }

    array_unique ( $xseason );
    sort ( $xseason );

    $value  = "1";
    $titles = DB ( 3, 'titles', $value, "", $conn, "" );

    $value  = "1";
    $stage = DB ( 3, 'stages', $value, "", $conn, "" );

    $value  = "1";
    $odd = DB ( 3, 'odds', $value, "", $conn, "" );
    $odds = [];
    $odds_id = [];
    while ( $x = mysqli_fetch_array ( $odd[1] ) )
    {
        $odds[] = $x['odd'];
        $odds_id[] = $x['id'];
    }

    $oddx = join ( ',', $odds );

    print (
        "<script>
            ODDS = [$oddx];
        </script>"
     );

    $value  = "1";
    $banks = DB ( 3, 'banks', $value, "", $conn, "" );
?>
<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta20
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Home</title>
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

<body>
    <script src="../dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page">
        <!-- Navbar -->
        <header class="navbar navbar-expand-md d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                    aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="./home.php">
                        <!-- <img src="../static/logo.svg" width="110" height="32" alt="Tabler" class="navbar-brand-image"> -->
                        <span>
                            USS
                        </span>
                    </a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">
                    <div class="nav-item dropdown">
                        <a href=".#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                            aria-label="Open user menu">
                            <span class="avatar avatar-sm">
                                <?php
                                    print ( $bio['name'][0] . $bio['name'][1] )
                                ?>
                            </span>
                            <div class="d-none d-xl-block ps-2">
                                <div style="text-transform:capitalize">
                                    <?php
                                        print ( $bio['name'] )
                                    ?>
                                </div>
                                <div class="mt-1 small text-secondary">Admin</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">   
                            <a href="?logout" class="dropdown-item">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <header class="navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="navbar">
                    <div class="container-xl">

                        <ul class="navbar-nav">

                            <li class="nav-item active" id="dash_body">
                                <a class="nav-link" href="./home.php">
                                    <span
                                        class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Home
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item" id="games_body">
                                <a class="nav-link" href="?games">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-chess-knight" width="44" height="44"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M8 16l-1.447 .724a1 1 0 0 0 -.553 .894v2.382h12v-2.382a1 1 0 0 0 -.553 -.894l-1.447 -.724h-8z" />
                                            <path
                                                d="M9 3l1 3l-3.491 2.148a1 1 0 0 0 .524 1.852h2.967l-2.073 6h7.961l.112 -5c0 -3 -1.09 -5.983 -4 -7c-1.94 -.678 -2.94 -1.011 -3 -1z" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Games
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item" id="fixture_body">
                                <a class="nav-link" href="?fixture">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-notes"
                                        width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M5 3m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                                        <path d="M9 7l6 0" />
                                        <path d="M9 11l6 0" />
                                        <path d="M9 15l4 0" />
                                    </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Bet
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="offcanvas" href="#offcanvasTop" role="button" aria-controls="offcanvasTop" onclick="Switch ( 'profilex', 'withdrawx' )">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-edit" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" />
                                        <path d="M18.42 15.61a2.1 2.1 0 0 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39z" />
                                    </svg>
                                    <span class="nav-link-title">
                                        Profile
                                    </span>
                                </a>
                            </li>

                            <!-- <li class="nav-item" id="profile_body">
                                <a class="nav-link" href="?profile">
                                    <span class="nav-link-title">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-user-star" width="44" height="44"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h.5" />
                                            <path
                                                d="M17.8 20.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138z" />
                                        </svg>
                                        Profile
                                    </span>
                                </a>
                            </li> -->

                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <div class="page-wrapper">
            <!-- Page body -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <!-- Page pre-title -->
                            <div class="page-pretitle" id="page_msg">
                                Overview
                            </div>
                            <h2 class="page-title" id="page_title">
                                Dashboard
                            </h2>
                        </div>
                        <!-- Page title actions -->
                        <div class="col-auto ms-auto d-print-none">
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="container-xl">

                    <div id="dash" style="display:none">
                        <div class="row row-cards">
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-primary text-white avatar">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-wallet" width="44"
                                                        height="44" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" />
                                                        <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    &#8358; <?php
                                                        print_r ( $wallet['amount'] );
                                                    ?>
                                                </div>
                                                <button onclick="Switch ('withdrawx', 'profilex')" data-bs-toggle="offcanvas" href="#offcanvasTop" role="button" aria-controls="offcanvasTop" class="text-secondary btn btn-sm">
                                                    Click to withdraw
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span
                                                    class="bg-green text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-ball-baseball" width="44"
                                                        height="44" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M5.636 18.364a9 9 0 1 0 12.728 -12.728a9 9 0 0 0 -12.728 12.728z" />
                                                        <path d="M12.495 3.02a9 9 0 0 1 -9.475 9.475" />
                                                        <path d="M20.98 11.505a9 9 0 0 0 -9.475 9.475" />
                                                        <path d="M9 9l2 2" />
                                                        <path d="M13 13l2 2" />
                                                        <path d="M11 7l2 1" />
                                                        <path d="M7 11l1 2" />
                                                        <path d="M16 11l1 2" />
                                                        <path d="M11 16l2 1" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    <?php
                                                        $row = mysqli_num_rows ( $my_games[1] );
                                                        if ( $row == 1 )
                                                        {
                                                            print ( "1 New Game" );
                                                        }
                                                        else if ( $row > 1 )
                                                        {
                                                            print ( $row . " New Games" );
                                                        }
                                                        else
                                                        {
                                                            print ( "No new games" );
                                                        }
                                                    ?>
                                                </div>
                                                <div class="text-secondary">
                                                    <?php
                                                        $row = mysqli_num_rows ( $played_games[1] );
                                                        if ( $row == 1 )
                                                        {
                                                            print ( "1 played game" );
                                                        }
                                                        else if ( $row > 1 )
                                                        {
                                                            print ( $row . " played games" );
                                                        }
                                                        else
                                                        {
                                                            print ( "No played games" );
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12" style="margin-bottom: 10px;">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Games for today</h3>
                                    </div>
                                    <div class="card-table table-responsive">
                                        <?php
                                            if ( !mysqli_num_rows ( $my_games[1] ) )
                                            {
                                                print (
                                                    '<div class="badge" style="width: 100%">
                                                        No record found
                                                    </div>'
                                                );
                                            }
                                            else
                                            {
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
                                                        while ( $x = mysqli_fetch_array ( $my_games[1] ) )
                                                        {
                                                            $date = strtolower ( $x['game_date'] );
                                                            $today = strtolower ( date ( "Y-m-d", time ( ) ) );
                                                            if ( $date == $today )
                                                            {
                                                ?>
                                                                <tr align="center">
                                                                    <td>
                                                                        <div style="font-size: 12px;text-transform: capitalize">
                                                                            <?php
                                                                                print ( $x['team_a'] )
                                                                            ?>
                                                                        </div>
                                                                        <div>
                                                                            <small class="badge bg-red-lt" style="text-transform: capitalize; font-size: 11px;">
                                                                                <?php print ( $x['game_date'] ) ?> | <?php print ( $x['game_time'] ) ?>
                                                                            </small>
                                                                            <small class="badge bg-primary-lt" style="font-size: 10px">
                                                                                <?php
                                                                                    $value  = "id = $x[title]";
                                                                                    $title = DB ( 3, 'titles', $value, "", $conn, "" );
                                                                                    $title = mysqli_fetch_array ( $title[1] );
                                                                                    print ( $title['title'] );
                                                                                ?>
                                                                            </small>
                                                                        </div>
                                                                        <div style="font-size: 12px;text-transform: capitalize">
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
                                                                                    '<span class="badge bg-red-lt" style="font-size: 10px">Still Pending</span>'
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
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Already played</h3>
                                    </div>
                                    <div class="card-table table-responsive">
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
                                                    while ( $x = mysqli_fetch_array ( $played_games[1] ) )
                                                    {
                                                        $date = strtolower ( $x['game_date'] );
                                                        $today = strtolower ( date ( "M d, Y", time ( ) ) );
                                                ?>
                                                        <tr align="center">
                                                            <td>
                                                                <div style="font-size: 12px;text-transform: capitalize">
                                                                    <?php
                                                                        print ( $x['team_a'] )
                                                                    ?>
                                                                </div>
                                                                <div>
                                                                    <small class="badge bg-red-lt" style="text-transform: capitalize; font-size: 10px;">
                                                                        <?php print ( $x['game_date'] ) ?> | <?php print ( $x['game_time'] ) ?>
                                                                    </small>
                                                                    <small class="badge bg-primary-lt" style="font-size: 10px">
                                                                        <?php
                                                                            $value  = "id = $x[title]";
                                                                            $title = DB ( 3, 'titles', $value, "", $conn, "" );
                                                                            $title = mysqli_fetch_array ( $title[1] );
                                                                            print ( $title['title'] );
                                                                        ?>
                                                                    </small>
                                                                </div>
                                                                <div style="font-size: 12px;text-transform: capitalize">
                                                                    <?php
                                                                        print ( $x['team_b'] )
                                                                    ?>
                                                                </div>
                                                            </td>
                                                            <td style="font-size: 12px">
                                                                <?php
                                                                    print ( $x['a_score'] )
                                                                ?> : <?php
                                                                print ( $x['b_score'] )
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock-check" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                    <path d="M20.942 13.021a9 9 0 1 0 -9.407 7.967" />
                                                                    <path d="M12 7v5l3 3" />
                                                                    <path d="M15 19l2 2l4 -4" />
                                                                </svg>
                                                                <!-- <span>
                                                                    Played
                                                                </span> -->
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" style="margin-top:10px;">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Top Teams</h3>
                                    </div>
                                    <div class="card-table table-responsive">
                                        <table class="table table-vcenter">
                                            <thead>
                                                <tr align="center">
                                                    <th>Teams</th>
                                                    <th>Win</th>
                                                    <th>Loss</th>
                                                    <th>Draw</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            
                                                for ( $i = 0; $i < count ( $xseason ); $i += 1 )
                                                {
                                                    $value  = "title = $xseason[$i] and season = 0";
                                                    $played_games = DB ( 3, 'new_games', $value, "", $conn, "" );

                                                    $win = [];
                                                    while ( $x = mysqli_fetch_array ( $played_games[1] ) )
                                                    {
                                                        $win[] = $x['team_a'];
                                                        $win[] = $x['team_b'];
                                                    }

                                                    $win = array_unique ( $win );
                                                    sort ( $win );
                                                    $times = [];
                                                    for ( $m = 0; $m < count ( $win ); $m ++ )
                                                    {
                                                        $value  = "winner = '$win[$m]' and season = 0   ";
                                                        $query = DB ( 2, 'new_games', $value, "", $conn, "" );
                                                        $times[] = $query[1];
                                                    }

                                                    // $times = array_unique ( $times );
                                                    // sort ( $times );
                                                    
                                                    if ( count ( $times ) )
                                                    {
                                                        $max = max ( $times );
                                                        $max = array_search ( $max, $times );
                                                        $name = $win[$max];
                                                        $times = $times[$max];
                                                        if ( $times )
                                                        {
                                                            $value  = "id = $xseason[$i]";
                                                            $title = DB ( 3, 'titles', $value, "", $conn, "" );
                                                            $title = mysqli_fetch_array ( $title[1] );
                                                            
                                                            $value  = "title = $xseason[$i] and season = 0 and team_a = '$name' || team_b = '$name'";
                                                            $played_games = DB ( 3, 'new_games', $value, "", $conn, "" );
                                                            $loss = 0;
                                                            $draw = 0;
                                                            while ( $x = mysqli_fetch_array ( $played_games[1] ) )
                                                            {
                                                                if ( $x['team_a'] == $name )
                                                                {
                                                                    $a = $x['a_score'];
                                                                    $b = $x['b_score'];
                                                                    if ( $a < $b )
                                                                    {
                                                                        $loss ++;
                                                                    }
                                                                    else if ( $a == $b )
                                                                    {
                                                                        $draw ++;
                                                                    }
                                                                }
                                                                
                                                                if ( $x['team_b'] == $name )
                                                                {
                                                                    $a = $x['a_score'];
                                                                    $b = $x['b_score'];
                                                                    if ( $b < $a )
                                                                    {
                                                                        $loss ++;
                                                                    }
                                                                    else if ( $a == $b )
                                                                    {
                                                                        $draw ++;
                                                                    }
                                                                }
                                                            }
                                            ?>
                                                            <tr align="center">
                                                                <td>
                                                                    <span class="text-secondary" style="text-transform:capitalize;font-size: 12px;">
                                                                        <?php
                                                                            print ( $name );
                                                                        ?>
                                                                    </span>
                                                                    <div>
                                                                        <span class="badge bg-lime text-lime-fg" style="font-size: 10px;">
                                                                            <?php
                                                                                print ( $title['title'] )
                                                                            ?>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td style="font-size: 12px">
                                                                    <?php
                                                                        print ( $times );
                                                                    ?>
                                                                </td>
                                                                <td style="font-size: 12px">
                                                                    <?php print ( $loss ) ?>
                                                                </td>
                                                                <td style="font-size: 12px">
                                                                    <?php print ( $draw ) ?>
                                                                </td>
                                                            </tr>
                                            <?php
                                                        }
                                                    }
                                                }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" style="margin-top:10px;">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Top Scorers</h3>
                                    </div>
                                    <div class="badge">
                                        No record found
                                    </div>
                                    <!-- <div class="card-table table-responsive">
                                        <table class="table table-vcenter">
                                            <thead>
                                                <tr align="center">
                                                    <th>Name</th>
                                                    <th>Goals</th>
                                                    <th>Team</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr align="center">
                                                    <td>
                                                        <span class="text-secondary">Shola Ademola</span>
                                                        <div>
                                                            <span class="badge bg-lime text-lime-fg">Vice Chancellor's
                                                                Cup</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        10
                                                    </td>
                                                    <td>
                                                        <span class="avatar mb-1 rounded">
                                                            SOE
                                                        </span>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="games" style="display:none">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="ribbon bg-red">NEW</div>
                                    <div class="card-body">
                                        <h3 class="card-title">
                                            <!-- Add game -->
                                        </h3>
                                        <form id="new_games" enctype="multipart/form-data">
                                            <input type="hidden" value="<?php print ( $bio['school'] ) ?>" id="school">
                                            <div class="form-floating mb-3">
                                                <select onchange="Custom ( this.value )" class="form-select"
                                                    id="game_title" aria-label="Floating label select example">
                                                    <option disabled>-- Title --</option>
                                                    <?php
                                                        while ( $x = mysqli_fetch_array ( $titles[1] ) )
                                                        {
                                                            print (
                                                                "<option value='$x[id]'>$x[title]</option>"
                                                            );
                                                        }
                                                    ?>
                                                    <option value="sk_custom">Others</option>
                                                </select>
                                                <label for="floating-input">
                                                    Game Title
                                                </label>
                                            </div>
                                            <div class="form-floating mb-3" id="custom_game" style="display:none">
                                                <input id="custom_game" class="form-control" autocomplete="off">
                                                <label for="floating-input">
                                                    Enter game title
                                                </label>
                                            </div>

                                            <div class="form-floating mb-3">
                                                <select id="game_stage" class="form-select" aria-label="Floating label select example">
                                                    <option disabled>-- Stage --</option>
                                                    <?php
                                                        while ( $x = mysqli_fetch_array ( $stage[1] ) )
                                                        {
                                                            print (
                                                                "<option value='$x[id]'>$x[title]</option>"
                                                            );
                                                        }
                                                    ?>
                                                </select>
                                                <label for="floating-input">
                                                    Game Stage
                                                </label>
                                            </div>

                                            <div class="form-floating mb-2" style="font-size: 12px">
                                                Add Teams ( Enter team names below )
                                            </div>
                                            <div id="game_teams">
                                                <div class="card" style="padding: 5px;">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3">
                                                                <input id="team_a" class="form-control" autocomplete="off">
                                                                <label for="floating-input">
                                                                    Team A ( Name )
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3">
                                                                <input id="team_b" class="form-control" autocomplete="off">
                                                                <label for="floating-input">
                                                                    Team B ( Name )
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <small class="badge bg-red-lt" style="margin-bottom: 10px;">
                                                                This section is mandatory
                                                            </small>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3">
                                                                <select id="a_odds" class="form-select"
                                                                    aria-label="Floating label select example">
                                                                    <?php
                                                                        for ( $i = 0; $i < count ( $odds ); $i ++ )
                                                                        {
                                                                            print (
                                                                                "<option value='$odds_id[$i]'>$odds[$i]</option>"
                                                                            );
                                                                        }
                                                                    ?>
                                                                </select>
                                                                <label for="floating-input">
                                                                    Team A Odds
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3">
                                                                <select id="b_odds" class="form-select"
                                                                    aria-label="Floating label select example">
                                                                    <?php
                                                                        for ( $i = 0; $i < count ( $odds ); $i ++ )
                                                                        {
                                                                            print (
                                                                                "<option value='$odds_id[$i]'>$odds[$i]</option>"
                                                                            );
                                                                        }
                                                                    ?>
                                                                </select>
                                                                <label for="floating-input">
                                                                    Team B Odds
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3">
                                                                <input id="game_date" type="date" class="form-control"
                                                                    autocomplete="off">
                                                                <label for="floating-input">
                                                                    Date
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3">
                                                                <input id="game_time" type="time" class="form-control"
                                                                    autocomplete="off">
                                                                <label for="floating-input">
                                                                    Time
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <small class="badge bg-primary-lt" style="margin-bottom: 10px;">
                                                                This section is optional
                                                            </small>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3">
                                                                <input id="a_logo" type="file" class="form-control"
                                                                    autocomplete="off">
                                                                <label for="floating-input">
                                                                    Team A logo
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3">
                                                                <input id="b_logo" type="file" class="form-control"
                                                                    autocomplete="off">
                                                                <label for="floating-input">
                                                                    Team B logo
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" value="0" id="team_point">
                                                </div>
                                            </div>
                                            <div style="margin-top: 10px;margin-bottom: 10px;">
                                                <button style="cursor:pointer" onclick="AddGame ( 0, ODDS )" class="btn btn-sm" type="button">
                                                    Add more
                                                </button>
                                            </div>
                                            <button type="button" id="hide_game" class="btn btn-md btn-danger w-100" style="display: none;" disabled="">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                Loading...
                                            </button>
                                            <button type="button" id="show_game" onclick="req = GetDataArray ( 'new_games' ); NewGame ( req, 'show_game', 'hide_game', 1, 'new_games' )" class="btn btn-md btn-danger w-100">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-device-floppy" width="44"
                                                    height="44" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                    <path d="M14 4l0 4l-6 0l0 -4" />
                                                </svg>Save
                                            </button>
                                            <!--<button type="button" onclick="req = GetDataArray ( 'new_games' ); NewGame ( req, 'show_game', 'hide_game', 1, 'new_games' )"" class="btn btn-md btn-danger">-->
                                            <!--    ttr-->
                                            <!--</button>-->
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="margin-bottom: 10px;">
                                    <div style="font-size: 17px;">
                                        My Games
                                    </div>
                                    <span style="font-size: 12px;color:grey">
                                        <?php
                                            $row = mysqli_num_rows ( $all_games[1] );
                                            if ( $row )
                                            {
                                                print (
                                                    "You have created $row game(s)"
                                                );
                                            }
                                            else
                                            {
                                                print (
                                                    'No games found'
                                                );
                                            }
                                        ?>
                                    </span>
                                </div>
                                
                                <div class="row">
                                    <?php
                                        $games = [];
                                        $closed = 0;
                                        while ( $x = mysqli_fetch_array ( $all_games[1] ) )
                                        {
                                            $games[] = $x['title'];
                                        }
                                        $games = array_unique ( $games );
                                        sort ( $games );
                                        
                                        for ( $i = 0; $i < count ( $games ); $i ++ )
                                        {
                                            mysqli_data_seek ( $all_games[1], 0 );
                                            $teams = [];

                                            $value  = "title = $games[$i]";
                                            $all_games    = DB ( 3, 'new_games', $value, "", $conn, "" );
                                            $count = mysqli_num_rows ( $all_games[1] );

                                            while ( $x = mysqli_fetch_array ( $all_games[1] ) )
                                            {
                                                $teams[] = $x['team_a'];
                                                $teams[] = $x['team_b'];
                                                $closed = $x['season'];
                                            }
                                            $teams = array_unique ( $teams );
                                            sort ( $teams );
                                            mysqli_data_seek ( $all_games[1], 0 );

                                            $value  = "id = $games[$i]";
                                            $title    = DB ( 3, 'titles', $value, "", $conn, "" );
                                            $title    = mysqli_fetch_array ( $title[1] );

                                            if ( $closed == 0 )
                                            {
                                                $type = "info";
                                            }
                                            else
                                            {
                                                $type = "success";
                                            }
                                    ?>
                                            <div class="col-md-6" style="margin-bottom: 20px;">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h3 class="card-title" style="text-transform: capitalize">
                                                            <?php
                                                                print ( $title['title'] );
                                                            ?>
                                                        </h3>
                                                        <span class="badge bg-lime text-lime-fg"><?php print ( $count ) ?> Game(s)</span>
                                                        <span class="badge bg-secondary text-lime-fg"><?php print ( count ( $teams ) ) ?> Team(s)</span>
                                                    </div>
                                                    <!-- Card footer -->
                                                    <div class="card-footer">
                                                        <button type="button" id="hide_close_<?php print ( $games[$i] ) ?>" class="btn btn-info btn-sm ms-auto" style="display: none;" disabled="">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                            Loading...
                                                        </button>
                                                        <a class="btn btn-<?php print ( $type )?> btn-sm ms-auto" id="show_close_<?php print ( $games[$i] ) ?>" onclick="GameClose ( <?php print ( $games[$i] ) ?>, 'show_close_<?php print ( $games[$i] ) ?>', 'hide_close_<?php print ( $games[$i] ) ?>', -10, 'new_games', <?php print ( $closed )?> )">
                                                            <?php
                                                                if ( !$closed )
                                                                {
                                                                    print (
                                                                        'Close'
                                                                    );
                                                                }
                                                                else
                                                                {
                                                                    print (
                                                                        'Open'
                                                                    );
                                                                }
                                                            ?>
                                                        </a>
                                                        <button onclick="EditGame ( 1 )" data-bs-toggle="offcanvas"
                                                            href="#offcanvasBottom_<?php print ( $games[$i] ) ?>" role="button"
                                                            aria-controls="offcanvasBottom_<?php print ( $games[$i] ) ?>"
                                                            class="btn btn-primary btn-sm ms-auto">
                                                            Edit
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="offcanvas offcanvas-bottom hide tabindex="-1 id="offcanvasBottom_<?php print ( $games[$i] ) ?>" aria-labelledby="offcanvasBottomLabel_<?php print ( $games[$i] ) ?>" aria-modal="true" role="dialog" style="height: 90%;">
                                                    <div class="offcanvas-header">
                                                        <h2 class="offcanvas-title" id="offcanvasBottomLabel_<?php print ( $games[$i] ) ?>">
                                                            <?php
                                                                print ( $title['title'] )
                                                            ?>
                                                        </h2>
                                                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                                    </div>
                                                    <div class="offcanvas-body">
                                                        <?php
                                                            $stage = [];
                                                            while ( $x = mysqli_fetch_array ( $all_games[1] ) )
                                                            {
                                                                $stage[] = $x['stage'];
                                                            }
                                                            
                                                            mysqli_data_seek ( $all_games[1], 0 );
                                                            $stage = array_unique ( $stage );
                                                            sort ( $stage );
                                                            
                                                            for ( $xx = 0; $xx < count ( $stage ); $xx ++ )
                                                            {
                                                                $value  = "id = $stage[$xx]";
                                                                $stages    = DB ( 3, 'stages', $value, "", $conn, "" );
                                                                $stages    = mysqli_fetch_array ( $stages[1] );
                                                        ?>
                                                                <div class="card" style="margin-bottom: 10px;">
                                                                    <div align="center" style="font-size: 14px;text-transform: uppercase" class="bg-primary-lt">
                                                                        <?php
                                                                            print ( $stages['title'] )
                                                                        ?>
                                                                    </div>
                                    <?php
                                        while ( $x = mysqli_fetch_array ( $all_games[1] ) )
                                        {
                                            if ( $x['stage'] == $stage[$xx] )
                                            {
                                                $value  = "id = $x[title]";
                                                $title    = DB ( 3, 'titles', $value, "", $conn, "" );
                                                $title    = mysqli_fetch_array ( $title[1] );
                                    ?>
                                                <div style="margin: 5px;" class="accordion" id="accordion-example_<?php print ($x['id'])?>" style="margin-bottom: 10px;">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="heading-1_<?php print ($x['id'])?>">
                                                            <button class="accordion-button " type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse-1_<?php print ($x['id'])?>"
                                                                aria-expanded="true">
                                                                <div style="text-transform: capitalize; font-size: 12px;">
                                                                    <?php
                                                                        print ( $x['team_a'] )
                                                                    ?>
                                                                </div>
                                                                <span class="badge bg-lime-lt" style="font-size: 12px; margin-left: 10px; margin-right: 10px;">VS</span>
                                                                <div style="text-transform: capitalize; font-size: 12px;">
                                                                    <?php
                                                                        print ( $x['team_b'] )
                                                                    ?>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse-1_<?php print ($x['id'])?>" class="accordion-collapse collapse hide"
                                                            data-bs-parent="#accordion-example">
                                                            <div class="accordion-body pt-0">
                                                                <?php
                                                                    require ( "games.php" );
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                    <?php
                                            }
                                        }
                                                mysqli_data_seek ( $all_games[1], 0 );
                                        ?>
                                                    </div>
                                <?php
                                    }
                                ?>
                                                        <button class="btn btn-danger btn-sm" type="button"
                                                            data-bs-dismiss="offcanvas" style="margin-top: 10px;">
                                                            Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="fixture" style="display:none">
                        <?php
                            require ( "fixtures.php" );
                        ?>
                    </div>

                    <!-- <div id="profile" style="display:none">3490jsef</div> -->

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
                </div>
            </div>

            <div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel" style="height: 93%">
              <div id="withdrawx" style="display:none">
                <div class="offcanvas-header">
                <h2 class="offcanvas-title" id="offcanvasTopLabel">

                </h2>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                <div class="page-pretitle ">
                    Send money to your local bank
                </div>
                <h2 class="page-title" style="margin-bottom: 10px;">
                    Withdraw
                </h2>

                <div class="row">
                    <div class="col-md-4">
                    <form id="withdraw">
                        <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="amount" autocomplete="off" value="0">
                        <label for="floating-input">(&#8358;) Enter Amount</label>
                        </div>
                        <div align="left" class="alert bg-lime-lt" id="acct_show" style="display:none"></div>
                        <div class="form-floating mb-3">
                        <input type="text" onclick="Verify ( this.value, $('#banks').val ( ) )" class="form-control" id="account" autocomplete="off">
                        <label for="floating-input">Account Number</label>
                        </div>
                        <div align="center" class="alert bg-red-lt" id="acct_hide" style="padding: 10px; display:none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </div>
                        <div class="form-floating">
                        <select onchange="Verify ( $('#account').val ( ), this.value )" class="form-select" id="banks" aria-label="Floating label select example">
                            <option selected disabled="">-- Select Bank --</option>
                            <?php
                                while ( $x = mysqli_fetch_array ( $banks[1] ) )
                                {
                            ?>
                                    <option value="<?php print ( $x['code'] ) ?>"><?php print ( $x['name'] ) ?></option>
                            <?php
                                }
                            ?>
                        </select>
                        <label for="floatingSelect">Select Bank</label>
                        </div>
                        <input type="hidden" id="acct_valid">
                        <br>
                        <button type="button" id="hide_with" class="btn btn-primary w-100" style="display: none;" disabled="">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                        </button>
                        <!-- <button onclick="req = DataObject ( 'withdraw' ); Withdraw ( req, 'show_with', 'hide_with', 1, 'withdraw' )" type="button">ss</button> -->
                        <button id="show_with" type="button" onclick="req = DataObject ( 'withdraw' ); Withdraw ( req, 'show_with', 'hide_with', -11, 'withdraw' )" class="btn btn-primary ms-auto" style="width: 100%">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-wallet" width="44"
                            height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                            d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" />
                            <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" />
                        </svg> Send
                        </button>
                    </form>
                    <br>
                    </div>
                    <div class="col-md-8">
                    <div class="card">
                        <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                while ( $x = mysqli_fetch_array ( $collect[1] ) )
                                {
                            ?>
                                    <tr>
                                        <td>
                                            <?php
                                                print ( date ( "M d, Y", $x['time'] ) . ' | ' . date ( "h:iA", $x['time'] ) )
                                            ?>
                                        </td>
                                        <td>
                                        &#8358; <?php
                                            print ( number_format ( $x['amount'] ) )
                                        ?>
                                        </td>
                                        <td class="text-secondary">
                                            <?php
                                                if ( $x['status'] == -1 )
                                                {
                                                    print ( '<span class="badge bg-red text-lime-fg">Pending</span>' );
                                                }
                                                else
                                                {
                                                print ( '<span class="badge bg-lime text-lime-fg">Completed</span>' );
                                                }
                                            ?>
                                        </td>
                                    </tr>
                            <?php
                                }
                            ?>
                            </tbody>
                        </table>
                        </div>
                        <div align="right">
                        <button class="btn btn-primary" type="button" data-bs-dismiss="offcanvas">
                            Close
                        </button>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
              </div>
              <div id="profilex" style="display:none">
                    <div class="offcanvas-header">
                    <h2 class="offcanvas-title" id="offcanvasTopLabel">

                    </h2>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                    <div class="row">
                    <div class="col-md-4"></div>
                        <div class="col-md-4">
                        <div class="page-pretitle ">
                            Update your profile here
                        </div>
                        <h2 class="page-title" style="margin-bottom: 10px;">
                            Profile
                        </h2>
                        <form id="main_users">
                            <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="name" value="<?php print ($bio['name'] )?>" autocomplete="off">
                            <label for="floating-input">Full Name</label>
                            </div>
                            <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="email" value="<?php print ($bio['email'] )?>" autocomplete="off">
                            <label for="floating-input">Email Address</label>
                            </div>
                            <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="phone" value="<?php print ($bio['phone'] )?>" autocomplete="off">
                            <label for="floating-input">Phone Number</label>
                            </div>
                            <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password">
                            <label for="floating-input">Old Password</label>
                            </div>
                            <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="user_new_pass">
                            <label for="floating-input">New Password</label>
                            </div>
                            <br>
                            <button type="button" id="hide_prof" class="btn btn-primary w-100" style="display: none;" disabled="">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                            </button>
                            <button type="button" id="show_prof"
                            onclick="req = DataObject ( 'main_users' ); Profile ( req, 'show_prof', 'hide_prof', 1, 'main_users', 1 )"
                            class="btn btn-primary w-100">
                            Sign In
                            </button>
                        </form>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                    </div>
                </div>
            </div>
            <div id="error_cont" class="alert alert-important alert-danger alert-dismissible" role="alert" style="position: fixed; top: 0%; right: 0px; display:none;z-index: 3; border-radius: 0px;">
                <div class="d-flex">
                    <div>
                        <!-- Download SVG icon from http://tabler-icons.io/i/alert-circle -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                        <path d="M12 8v4"></path>
                        <path d="M12 16h.01"></path>
                        </svg>
                    </div>
                    <div class="d-flex" id="error_msg"></div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            </div>
            <!-- Libs JS -->
            <!-- Tabler Core -->
            <script src="../dist/js/tabler.min.js?1692870487" defer></script>
            <script src="../dist/js/demo.min.js?1692870487" defer></script>
            <script src="../dist/js/jone.js" defer></script>
            <script src="../dist/js/backend.js" defer></script>
            <script src="../dist/js/admin.js" defer></script>
</body>

</html>