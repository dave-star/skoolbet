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
          '<meta http-equiv="refresh" content="0;../signin"></meta>'
        );
        exit ( 1 );
    }
    require ( "../backend/global.php" );

    $main_id = $_SESSION['id'];
    $conn = Database ( );

    $obj    = DB ( 4, 'schools', "", "", $conn, '' );
    $skool = $obj[1];

    $value  = "id = $main_id";
    $bio    = DB ( 3, 'users', $value, "", $conn, "" );
    $bio    = mysqli_fetch_array ( $bio[1] );
    $_SESSION['school'] = $bio['school'];

    $value  = "sid = $main_id";
    $wallet    = DB ( 3, 'wallet', $value, "", $conn, "" );
    $wallet    = mysqli_fetch_array ( $wallet[1] );

    $value  = "id = $bio[school]";
    $sch    = DB ( 3, 'schools', $value, "", $conn, "" );
    $sch    = mysqli_fetch_array ( $sch[1] );

    $games    = DB ( 3, 'games', $value, "", $conn, "" );
    // $games    = mysqli_fetch_array ( $games[1] );
    
    $value          = "sid = $main_id";
    $total_games    = DB ( 3, 'games', $value, "", $conn, "" );

    $value  = "sid = $main_id";
    $payment    = DB ( 3, 'payment', $value, "", $conn, "" );

    $value  = "school = $bio[school] and status = 0";
    $new_games    = DB ( 3, 'new_games', $value, "", $conn, "" );
    // $new_games    = mysqli_fetch_array ( $new_games[1] );
    $titles = [];
    while ( $x = mysqli_fetch_array ( $new_games[1] ) )
    {
        $titles[] = $x['title'];
    }
    
    $titles = array_unique ( $titles );
    sort ( $titles );

    $value  = "school != $bio[school] and status != 2";
    $o_games = DB ( 3, 'new_games', $value, "", $conn, "" );
    $others = [];
    while ( $x = mysqli_fetch_array ( $o_games[1] ) )
    {
        $others[] = $x['school'];
    }
    $others = array_unique ( $others );
    sort ( $others);

    $value  = "winner=''";
    $xfilter = DB ( 3, 'new_games', $value, "", $conn, "" );
    $filter = [];
    while ( $x = mysqli_fetch_array ( $xfilter[1] ) )
    {
        $a  = "id=$x[school]";
        $bi  = DB ( 3, 'schools', $a, "", $conn, "" );
        $b  = mysqli_fetch_array ( $bi[1] );
        $filter[] = $b['short'];
    }
    $filter = array_unique ( $filter );
    sort ( $filter);

    $value  = "1";
    $banks = DB ( 3, 'banks', $value, "", $conn, "" );

    $value  = "sid = $main_id";
    $collect = DB ( 3, 'withdraw', $value, "", $conn, "" );

    $value  = "1";
    $courses = DB ( 3, 'courses', $value, "", $conn, "" );

    $value  = "1";
    $time = DB ( 3, 'levels', $value, "", $conn, "" );
    // exit ( 1)
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
  <title>Skool Bet</title>
  <!-- CSS files -->
  <link href="../dist/css/tabler.min.css?1692870487" rel="stylesheet" />
  <link href="../dist/css/tabler-flags.min.css?1692870487" rel="stylesheet" />
  <link href="../dist/css/tabler-payments.min.css?1692870487" rel="stylesheet" />
  <link href="../dist/css/tabler-vendors.min.css?1692870487" rel="stylesheet" />
  <link href="../dist/css/demo.min.css?1692870487" rel="stylesheet" />
  <link href="../dist/css/index.css" rel="stylesheet" />
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
<div class="page page-center" id="loader" style="position: fixed; top: 0%;left: 0%; width: 100%; height: 100%">
  <div class="container container-slim py-4">
    <div class="text-center">
      <div class="mb-3">
        <a href="." class="navbar-brand navbar-brand-autodark">
          <!-- <img src="../static/logo-small.svg" alt="" height="36"> -->
          <div class="main_logo bg-primary">
            <span class="main_s">S<span class="main_b">B</span></span>
          </div>
        </a>
      </div>
      <div class="text-secondary mb-3">Preparing application</div>
      <div class="progress progress-sm">
        <div class="progress-bar progress-bar-indeterminate"></div>
      </div>
    </div>
  </div>
</div>

<body class="main_body" id="main_body">
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
          <a href="..">
            <!-- <img src="../static/logo.svg" width="110" height="32" alt="Tabler" class="navbar-brand-image"> -->
            <div class="main_logo bg-primary">
              <span class="main_s">S<span class="main_b">B</span></span>
            </div>
          </a>
        </h1>
        <div class="navbar-nav flex-row order-md-last">
          
          <div class="nav-item dropdown">
            <a href=".#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
              aria-label="Open user menu">
              <!-- <span class="avatar avatar-sm" style="background-image: url(../static/avatars/000m.jpg)"></span> -->
              <span class="avatar avatar-x2 mb-1 rounded">
                <?php
                    print ( $bio['name'][0] . $bio['name'][1] )
                ?>
              </span>
              <div class="d-none d-xl-block ps-2">
                <div style="text-transform: capitalize">
                    <?php
                        print ( $bio['name'] )
                    ?>
                </div>
                <div class="mt-1 small text-danger">&#8358; <?php
                    print ( number_format ( $wallet['amount'] ) );
                ?></div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <a href="./?logout" class="dropdown-item">Logout</a>
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
              <li class="nav-item active">
                <a class="nav-link" href="./">
                  <span
                    class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                      stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
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

              <li class="nav-item">
                <a id="my_games_tab"
                  onclick="Modal ( 'my_games_played', ['my_games_played', 'withdraw', 'fund', 'profile'] )"
                  class="nav-link" data-bs-toggle="offcanvas" href="#offcanvasBottom" role="button"
                  aria-controls="offcanvasBottom">
                  <span
                    class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-gamepad-3"
                      width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                      stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M10 12l-3 -3h-2a1 1 0 0 0 -1 1v4a1 1 0 0 0 1 1h2l3 -3z" />
                      <path d="M14 12l3 -3h2a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-2l-3 -3z" />
                      <path d="M12 14l-3 3v2a1 1 0 0 0 1 1h4a1 1 0 0 0 1 -1v-2l-3 -3z" />
                      <path d="M12 10l-3 -3v-2a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v2l-3 3z" />
                    </svg>
                  </span>
                  <span class="nav-link-title">
                    My Games
                  </span>
                </a>
              </li>

              <li class="nav-item">
                <a id="my_fund" onclick="Modal ( 'fund', ['my_games_played', 'withdraw', 'fund', 'profile'] )"
                  class="nav-link" data-bs-toggle="offcanvas" href="#offcanvasBottom" role="button"
                  aria-controls="offcanvasBottom">
                  <span
                    class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                    <svg xmlns="http://www.w3.org/2000/svg"
                      class="icon icon-tabler icon-tabler-device-ipad-horizontal-plus" width="44" height="44"
                      viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round"
                      stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M12 20h-7a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v6.5" />
                      <path d="M9 17h3.5" />
                      <path d="M16 19h6" />
                      <path d="M19 16v6" />
                    </svg>
                  </span>
                  <span class="nav-link-title">
                    Fund Account
                  </span>
                </a>
              </li>

              <li class="nav-item">
                <a onclick="Modal ( 'withdraw', ['my_games_played', 'withdraw', 'fund', 'profile'] )" class="nav-link"
                  data-bs-toggle="offcanvas" href="#offcanvasBottom" role="button" aria-controls="offcanvasBottom">
                  <span
                    class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-credit-card-refund"
                      width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                      stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M12 19h-6a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v4.5" />
                      <path d="M3 10h18" />
                      <path d="M7 15h.01" />
                      <path d="M11 15h2" />
                      <path d="M16 19h6" />
                      <path d="M19 16l-3 3l3 3" />
                    </svg>
                  </span>
                  <span class="nav-link-title">
                    Withdraw
                  </span>
                </a>
              </li>

              <li class="nav-item">
                <a onclick="Modal ( 'profile', ['my_games_played', 'withdraw', 'fund', 'profile'] )" class="nav-link"
                  data-bs-toggle="offcanvas" href="#offcanvasBottom" role="button" aria-controls="offcanvasBottom">
                  <span
                    class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-edit" width="44"
                      height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                      stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                      <path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" />
                      <path d="M18.42 15.61a2.1 2.1 0 0 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39z" />
                    </svg>
                  </span>
                  <span class="nav-link-title">
                    Profile
                  </span>
                </a>
              </li>

            </ul>
          </div>
        </div>
      </div>
    </header>

    <div class="page-wrapper">
      <!-- Page body -->
      <div class="page-body">
        <div class="container-xl d-flex flex-column justify-content-center">
          <div class="col">

            <div class="row" style="margin-bottom: 10px;">
              <div class="col-12">
                <div class="row row-cards">
                  <div class="col-sm-6 col-lg-4">
                    <div class="card card-sm">
                      <div class="card-body">
                        <div class="row align-items-center">
                          <div class="col-auto">
                            <span
                              class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-wallet"
                                width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                  d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" />
                                <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" />
                              </svg>
                            </span>
                          </div>
                          <div class="col">
                            <div class="font-weight-medium" style="font-size: 16px;">
                              &#8358; <?php
                                  print ( number_format ( $wallet['amount'] ) );
                              ?>
                            </div>
                            <a href="#" onclick="document.getElementById('my_fund').click()" class="text-secondary">
                              Click to withdraw
                            </a>
                            <button onclick="document.getElementById('my_fund').click()" class="btn"
                              style="position: absolute; top:15px; right: 20px; font-size: 20px;">
                              &plus;
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 col-lg-4">
                    <div class="card card-sm">
                      <div class="card-body">
                        <div class="row align-items-center">
                          <div class="col-auto">
                            <span
                              class="bg-danger text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                              <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-device-gamepad-2" width="44" height="44"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                  d="M12 5h3.5a5 5 0 0 1 0 10h-5.5l-4.015 4.227a2.3 2.3 0 0 1 -3.923 -2.035l1.634 -8.173a5 5 0 0 1 4.904 -4.019h3.4z" />
                                <path d="M14 15l4.07 4.284a2.3 2.3 0 0 0 3.925 -2.023l-1.6 -8.232" />
                                <path d="M8 9v2" />
                                <path d="M7 10h2" />
                                <path d="M14 10h2" />
                              </svg>
                            </span>
                          </div>
                          <div class="col">
                            <div class="font-weight-medium" style="font-size: 16px;">
                              <?php
                                  $row = mysqli_num_rows ( $games[1] );
                                  if ( $row )
                                  {
                                      $msg = $row . " games played";
                                  }
                                  else
                                  {
                                    $msg = "No games played";
                                  }

                                  print ( $msg );
                              ?>
                            </div>
                            <a href="#" class="text-secondary"
                              onclick="document.getElementById('my_games_tab').click()">
                              Click to see all your bet
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 col-lg-4">
                    <div class="card card-sm">
                      <div class="card-body">
                        <div class="row align-items-center">
                          <div class="col-auto">
                            <span
                              class="bg-info text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checkup-list" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                <path d="M9 14h.01" />
                                <path d="M9 17h.01" />
                                <path d="M12 16l1 1l3 -3" />
                              </svg>
                            </span>
                          </div>
                          <div class="col">
                            <div class="font-weight-medium" style="font-size: 16px;">
                              Bet Slip
                            </div>
                            <a href="#" onclick="$('#bet_cont').show ( 200 )" class="text-secondary">
                              View Slip
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                  <li class="nav-item" role="presentation">
                    <a href="#tabs-home-1" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ping-pong" width="44"
                        height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                          d="M12.718 20.713a7.64 7.64 0 0 1 -7.48 -12.755l.72 -.72a7.643 7.643 0 0 1 9.105 -1.283l2.387 -2.345a2.08 2.08 0 0 1 3.057 2.815l-.116 .126l-2.346 2.387a7.644 7.644 0 0 1 -1.052 8.864" />
                        <path d="M14 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                        <path d="M9.3 5.3l9.4 9.4" />
                      </svg> Sports
                    </a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a href="#tabs-profile-1" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab"
                      tabindex="-1">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-pencil"
                        width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                        <path d="M10 18l5 -5a1.414 1.414 0 0 0 -2 -2l-5 5v2h2z" />
                      </svg> Academics
                    </a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active show" id="tabs-home-1" role="tabpanel">
                    <div class="row">
                      <div class="col-md-8">
                        <div class="input-icon mb-3">
                          <input oninput="FilterTeam ( 'main_child', this.value )" type="text" class="form-control" placeholder="Search…">
                          <span class="input-icon-addon">
                            <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                              viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                              stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                              <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                              <path d="M21 21l-6 -6"></path>
                            </svg>
                          </span>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="input-icon mb-3">
                          <select onchange="Filter ( 'main_mum', this.value )" class="form-control">
                              <option selected disabled>
                                -- Filter By School -- 
                              </opiton>
                              <option>
                                  All
                              </opiton>
                              <?php
                                  for ( $i = 0; $i < count ( $filter ); $i += 1 )
                                  {
                                      print (
                                        "<option>$filter[$i]</option>"
                                      );
                                  }
                              ?>
                          </select>
                          <span class="input-icon-addon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-badge-down" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <path d="M17 13v-6l-5 4l-5 -4v6l5 4z" />
                            </svg>
                          </span>
                        </div>
                      </div>
                    </div>

                    <div class="main_mum">
                      <!-- For user's school -->
                      <div class="page-pretitle ">
                        New
                      </div>
                      <h2 class="page-title" style="margin-bottom: 10px;">
                        <?php
                            print ( $sch['short'] )
                        ?> Games
                      </h2>

                      <?php
                          if ( !mysqli_num_rows ( $new_games[1] ) )
                          {
                      ?>
                              <div class="alert alert-danger" role="alert">
                                <div class="d-flex">
                                  <div>
                                    <!-- Download SVG icon from http://tabler-icons.io/i/alert-circle -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                                      viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                      stroke-linecap="round" stroke-linejoin="round">
                                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                      <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                                      <path d="M12 8v4"></path>
                                      <path d="M12 16h.01"></path>
                                    </svg>
                                  </div>
                                  <div>
                                    <h4 class="alert-title">
                                      No Games
                                    </h4>
                                    <div class="text-secondary">
                                      No ongoing games in your school.
                                    </div>
                                  </div>
                                </div>
                              </div>
                      <?php
                          }
                          else
                          {
                      ?>
                              <div class="card"
                                style="border: var(--tblr-border-width) var(--tblr-border-style) var(--tblr-border-color-translucent); padding: 0px;">
                                <div class="row">

                                  <div class="col-md-8">
                                    <?php
                                        for ( $i = 0; $i < count ( $titles ); $i += 1 )
                                        {
                                            $value  = "school = $bio[school] and title = $titles[$i]";
                                            $new_games    = DB ( 3, 'new_games', $value, "", $conn, "" );

                                            $value    = "id = $titles[$i]";
                                            $title    = DB ( 3, 'titles', $value, "", $conn, "" );
                                            $title    = mysqli_fetch_array ( $title[1] );

                                            print (
                                              '<div class="badge badge-md bg-lime-lt" style="border-radius: 2px; margin-bottom: 10px; font-size: 14px">' . $title['title'] . '</div>'
                                            );

                                            while ( $x = mysqli_fetch_array ( $new_games[1] ) )
                                            {
                                                $valuex    = "id = $x[a_odds] or id = $x[b_odds]";
                                                $odds    = DB ( 3, 'odds', $valuex, "", $conn, "" );

                                                $valuex    = "gid = $x[id] and sid = $main_id";
                                                $played    = DB ( 3, 'games', $valuex, "", $conn, "" );
                                                $played    = mysqli_num_rows ( $played[1] );

                                                while ( $od = mysqli_fetch_array ( $odds[1] ) )
                                                {
                                                    if ( $od['id'] == $x['a_odds'] )
                                                    {
                                                        $a_odds = $od['odd'];
                                                    }
                                                    else if ( $od['id'] == $x['b_odds'] )
                                                    {
                                                        $b_odds = $od['odd'];
                                                    }
                                                }
                                    ?>
                                                <div style="padding: 5px;" class="main_child">
                                                  <span style="display: none"><?php print ( $sch['short']) ?></span>
                                                  <div class="row">

                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                      <div class="row">
                                                        <div class="col-md-5">
                                                          <a href="#" class="card card-link">
                                                            <div>
                                                              <div class="form-selectgroup-label-content d-flex align-items-center" style="padding: 3px;">
                                                                 <?php
                                                                    if ( $x['a_logo'] == 'undefined' )
                                                                    {
                                                                        $img = GetShort ( $x['team_a'] );
                                                                        print (
                                                                          "<span class='avatar me-1' style='font-size: 11px;'>$img</span>"
                                                                        );
                                                                    }
                                                                    else
                                                                    {
                                                                        print (
                                                                          "<span class='avatar' style='background-image: url(../static/teams/$x[a_logo])'></span>&nbsp;"
                                                                        );
                                                                    }
                                                                 ?>
                                                                <div class="ellips">
                                                                  <div class="text-secondary ellipx" style="text-transform: capitalize"><?php print ( $x['team_a'] );?></div>
                                                                </div>
                                                                <button class="btn btn-md btn-danger score">
                                                                  <?php
                                                                      print ( $x['a_score'] )
                                                                  ?>
                                                                </button>
                                                              </div>
                                                            </div>
                                                          </a>
                                                          <div class="badge bg-azure-lt" style="font-size: 9px;text-transform: capitalize">
                                                            <?php print ( $x['game_date'] ) ?> | <?php print ( $x['game_time'] ) ?>
                                                          </div>
                                                        </div>

                                                        <div class="col-md-1" align="center" style="padding: 0px;">
                                                          <div class="vs">
                                                            <span class="badge">
                                                              VS
                                                            </span>
                                                          </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                          <a href="#" class="card card-link">
                                                            <div class="">
                                                              <div class="form-selectgroup-label-content d-flex align-items-center" style="padding: 3px">
                                                                <!-- <span class="avatar me-3">SOC</span> -->
                                                                <?php
                                                                    if ( $x['b_logo'] == 'undefined' )
                                                                    {
                                                                        $img = GetShort ( $x['team_a'] );
                                                                        print (
                                                                          "<span class='avatar me-1' style='font-size: 11px;'>$img</span>"
                                                                        );
                                                                    }
                                                                    else
                                                                    {
                                                                        print (
                                                                          "<span class='avatar' style='background-image: url(../static/teams/$x[b_logo])'></span>&nbsp;"
                                                                        );
                                                                    }
                                                                 ?>
                                                                <div class="ellips">
                                                                <div class="text-secondary ellipx" style="text-transform: capitalize"><?php print ( $x['team_b'] );?></div>
                                                                </div>
                                                                <button class="btn btn-md btn-danger score">
                                                                  <?php
                                                                      print ( $x['b_score'] )
                                                                  ?>
                                                                </button>
                                                              </div>
                                                            </div>
                                                          </a>
                                                        </div>

                                                        <div class="col-md-1" align="left" style="padding:0px;">
                                                          <?php if ( !$played ) { ?>
                                                          <button onclick="$('#bet_options_<?php print ( $x['id'] ) ?>').show ( 200 );" class="btn btn-sm btn-danger"
                                                            style="border:0px; width: 100%;padding: 10px;margin-top: 5px;">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                              class="icon icon-tabler icon-tabler-plus" width="44" height="44"
                                                              viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                                              stroke-linecap="round" stroke-linejoin="round">
                                                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                              <path d="M12 5l0 14" />
                                                              <path d="M5 12l14 0" />
                                                            </svg>
                                                            <span class="">Play</span>
                                                          </button>
                                                          <?php } else { ?>
                                                          <button class="btn btn-sm btn-success" style="border:0px; width: 100%;padding: 10px;margin-top: 5px;">
                                                            Played
                                                          </button>
                                                          <?php } ?>
                                                        </div>

                                                        <!-- <div id="bet_now" class="col-md-2">
                                                          <button onclick="$('#bet_options').show ( 200 ); $('#bet_now').hide ( 200 )"
                                                            class="btn btn-primary w-100" style="height: 100%">
                                                            Bet Now
                                                          </button>
                                                        </div> -->

                                                      </div>
                                                    </div>

                                                  </div>

                                                  <div class="card card-body" style="display:none" id="bet_options_<?php print ( $x['id'] )?>">
                                                    <h5>
                                                      <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-adjustments-horizontal" width="44" height="44"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M14 6m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                        <path d="M4 6l8 0" />
                                                        <path d="M16 6l4 0" />
                                                        <path d="M8 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                        <path d="M4 12l2 0" />
                                                        <path d="M10 12l10 0" />
                                                        <path d="M17 18m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                        <path d="M4 18l11 0" />
                                                        <path d="M19 18l1 0" />
                                                      </svg> Game Settings
                                                    </h5>
                                                    <form>
                                                      <input type="hidden" id="teams_<?php print ( $x['id'] ) ?>" value="<?php print ($x['team_a'])?>,<?php print ($x['team_b'])?>">
                                                      <div class="form-floating" style="margin-bottom: 10px;">
                                                        <select id="teams_bet_type_<?php print( $x['id'] ) ?>" onchange="SetTeam ( '', <?php print ( $x['id'] )?> );BetOptions ( this.value, <?php print ( $x['id'] ) ?> );"
                                                          class="form-select"
                                                          aria-label="Floating label select example">
                                                          <option selected disabled>-- Select --</option>
                                                          <option value="1">Win (extra time or penalty)</option>
                                                          <option value="2">Straight Win</option>
                                                          <option value="3">Both teams to score</option>
                                                          <option value="4">Draw</option>
                                                        </select>
                                                        <label for="floatingSelect">Bet Type</label>
                                                      </div>

                                                      <div id="bet_teams_<?php print ( $x['id'] ) ?>" class="form-floating" style="margin-bottom: 10px;display: block">
                                                        <select id="teams_value_<?php print ( $x['id'] ) ?>" onchange="SetTeam ( this.value, <?php print ( $x['id'] )?> );Odds ( this.value, undefined, <?php print ( $x['id'] ) ?> )" class="form-select" aria-label="Floating label select example" style="text-transform: capitalize">
                                                          <option selected value="0">-- Select --</option>
                                                          <option value="<?php print ( $x['team_a'] ) ?>, <?php print ( $a_odds ) ?>"><?php print ( $x['team_a'] ) ?>: <?php print ( $a_odds ) ?> odds</option>
                                                          <option value="<?php print ( $x['team_b'] ) ?>, <?php print ( $b_odds ) ?>"><?php print ( $x['team_b'] ) ?>: <?php print ( $b_odds) ?> odds</option>
                                                        </select>
                                                        <label for="floatingSelect">Select Team</label>
                                                      </div>

                                                      <!-- <div class="form-floating" style="margin-bottom: 10px;">
                                                        <input type="email" class="form-control" id="floating-input" autocomplete="off">
                                                        <label for="floatingSelect">Enter Amount (&#8358; 50 min.)</label>
                                                      </div> -->

                                                      <div class="form-floating" style="margin-bottom: 10px;margin-top: 10px;"
                                                        align="center">
                                                        <button onclick="AddGame ( <?php print ( $x['id'] ) ?> )" type="button" class="btn btn-sm btn-success"
                                                          style="width: 70%" id="add_odds_<?php print( $x['id'] ) ?>">
                                                          <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-device-gamepad-2" width="44" height="44"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                              d="M12 5h3.5a5 5 0 0 1 0 10h-5.5l-4.015 4.227a2.3 2.3 0 0 1 -3.923 -2.035l1.634 -8.173a5 5 0 0 1 4.904 -4.019h3.4z" />
                                                            <path d="M14 15l4.07 4.284a2.3 2.3 0 0 0 3.925 -2.023l-1.6 -8.232" />
                                                            <path d="M8 9v2" />
                                                            <path d="M7 10h2" />
                                                            <path d="M14 10h2" />
                                                          </svg> Add (<span id="odd_cnt_<?php print ( $x['id']) ?>">0</span>&nbsp;odds)
                                                        </button>
                                                        <button onclick="$('#bet_options_<?php print ( $x['id'] ) ?>').hide ( 200 ); $('#bet_now_<?php print ( $x['id'] )?>').show ( 200 )"
                                                          type="button" class="btn btn-sm btn-danger" style="width: 20%">
                                                          Hide
                                                        </button>
                                                      </div>
                                                    </form>
                                                  </div>

                                                </div>
                                    <?php
                                            }
                                        }
                                    ?>
                                  </div>
                                  <div class="col-md-4"
                                    style="border-left: var(--tblr-border-width) var(--tblr-border-style) var(--tblr-border-color-translucent);">
                                    <div class="table-responsive">

                                      <div class="top_stand bg-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                          class="icon icon-tabler icon-tabler-ball-volleyball" width="44" height="44"
                                          viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                          stroke-linecap="round" stroke-linejoin="round">
                                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                          <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                          <path d="M12 12a8 8 0 0 0 8 4" />
                                          <path d="M7.5 13.5a12 12 0 0 0 8.5 6.5" />
                                          <path d="M12 12a8 8 0 0 0 -7.464 4.928" />
                                          <path d="M12.951 7.353a12 12 0 0 0 -9.88 4.111" />
                                          <path d="M12 12a8 8 0 0 0 -.536 -8.928" />
                                          <path d="M15.549 15.147a12 12 0 0 0 1.38 -10.611" />
                                        </svg> TOP TEAMS
                                      </div>
                                      <?php
                                          $school = $bio['school'];
                                          require ( "teams.php" );
                                      ?>
                                    </div>
                                  </div>

                                </div>
                              </div>
                      <?php
                          }
                      ?>

                    </div>

                    <!-- Other schools -->
                     <?php
                        
                        for ( $i = 0; $i < count ( $others ); $i += 1 )
                        {
                            $value  = "school = $others[$i]";
                            $o_games = DB ( 3, 'new_games', $value, "", $conn, "" );

                            $value  = "id = $others[$i]";
                            $sch    = DB ( 3, 'schools', $value, "", $conn, "" );
                            $sch    = mysqli_fetch_array ( $sch[1] );
                    ?>
                            <div class="main_mum">
                                <div class="page-pretitle" style="margin-top: 10px;">
                                  New
                                </div>
                                <h2 class="page-title" style="margin-bottom: 10px;">
                                  <?php
                                      print ( $sch['short'] )
                                  ?> Games
                                </h2>
                                
                                <div class="card"
                                style="border: var(--tblr-border-width) var(--tblr-border-style) var(--tblr-border-color-translucent); padding: 0px;">
                                  <div class="row">
                                      <div class="col-md-8">
                                          <?php
                                              while ( $x = mysqli_fetch_array ( $o_games[1] ) )
                                              {
                                                  $value    = "id = $x[title]";
                                                  $title    = DB ( 3, 'titles', $value, "", $conn, "" );
                                                  $title    = mysqli_fetch_array ( $title[1] );
    
                                                  $valuex    = "gid = $x[id] and sid = $main_id";
                                                  $played    = DB ( 3, 'games', $valuex, "", $conn, "" );
                                                  $played    = mysqli_num_rows ( $played[1] );

                                                  print (
                                                    '<div class="badge badge-md bg-lime-lt" style="border-radius: 2px; margin-bottom: 10px; font-size: 14px">' . $title['title'] . '</div>'
                                                  );

                                                  $school = $x['school'];
                                                  $valuex    = "id = $x[a_odds] or id = $x[b_odds]";
                                                  $odds    = DB ( 3, 'odds', $valuex, "", $conn, "" );
                                                  while ( $od = mysqli_fetch_array ( $odds[1] ) )
                                                  {
                                                      if ( $od['id'] == $x['a_odds'] )
                                                      {
                                                          $a_odds = $od['odd'];
                                                      }
                                                      else if ( $od['id'] == $x['b_odds'] )
                                                      {
                                                          $b_odds = $od['odd'];
                                                      }
                                                  }
                                          ?>
                                                  <div style="padding: 5px;" class="main_child">
                                                    <span style="display:none"><?php print ( $sch['short']) ?></span>

                                                    <div class="row">

                                                      <div class="col-md-12" style="margin-bottom: 10px;">
                                                        <div class="row">
                                                          <div class="col-md-5">
                                                            <a href="#" class="card card-link">
                                                              <div>
                                                                <div class="form-selectgroup-label-content d-flex align-items-center" style="padding: 3px;">
                                                                  <?php
                                                                      if ( $x['a_logo'] == 'undefined' )
                                                                      {
                                                                          $img = GetShort ( $x['team_a'] );
                                                                          print (
                                                                            "<span class='avatar me-1' style='font-size: 11px;'>$img</span>"
                                                                          );
                                                                      }
                                                                      else
                                                                      {
                                                                          print (
                                                                            "<span class='avatar' style='background-image: url(../static/teams/$x[a_logo])'></span>&nbsp;"
                                                                          );
                                                                      }
                                                                  ?>
                                                                  <div class="ellips">
                                                                    <div class="text-secondary ellipx" style="text-transform: capitalize"><?php print ( $x['team_a'] );?></div>
                                                                  </div>
                                                                  <button class="btn btn-md btn-danger score">
                                                                    <?php
                                                                        print ( $x['a_score'] )
                                                                    ?>
                                                                  </button>
                                                                </div>
                                                              </div>
                                                            </a>
                                                            <div class="badge bg-azure-lt" style="font-size: 9px;text-transform: capitalize">
                                                              <?php print ( $x['game_date'] ) ?> | <?php print ( $x['game_time'] ) ?>
                                                            </div>
                                                          </div>

                                                          <div class="col-md-1" align="center" style="padding: 0px;">
                                                            <div class="vs">
                                                              <span class="badge">
                                                                VS
                                                              </span>
                                                            </div>
                                                          </div>

                                                          <div class="col-md-5">
                                                              <a href="#" class="card card-link">
                                                                <div class="">
                                                                  <div class="form-selectgroup-label-content d-flex align-items-center" style="padding: 3px">
                                                                    <!-- <span class="avatar me-3">SOC</span> -->
                                                                    <?php
                                                                        if ( $x['b_logo'] == 'undefined' )
                                                                        {
                                                                            $img = GetShort ( $x['team_a'] );
                                                                            print (
                                                                              "<span class='avatar me-1' style='font-size: 11px;'>$img</span>"
                                                                            );
                                                                        }
                                                                        else
                                                                        {
                                                                            print (
                                                                              "<span class='avatar' style='background-image: url(../static/teams/$x[b_logo])'></span>&nbsp;"
                                                                            );
                                                                        }
                                                                    ?>
                                                                    <div class="ellips">
                                                                    <div class="text-secondary ellipx" style="text-transform: capitalize"><?php print ( $x['team_b'] );?></div>
                                                                    </div>
                                                                    <button class="btn btn-md btn-danger score">
                                                                      <?php
                                                                          print ( $x['b_score'] )
                                                                      ?>
                                                                    </button>
                                                                  </div>
                                                                </div>
                                                              </a>
                                                          </div>

                                                          <div class="col-md-1" align="left" style="padding:0px;">
                                                            <?php if ( !$played ) { ?>
                                                            <button onclick="$('#bet_options_<?php print ( $x['id'] ) ?>').show ( 200 );" class="btn btn-sm btn-danger"
                                                              style="border:0px; width: 100%;padding: 10px;margin-top: 5px;">
                                                              <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-plus" width="44" height="44"
                                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M12 5l0 14" />
                                                                <path d="M5 12l14 0" />
                                                              </svg>
                                                              <span class="">Play</span>
                                                            </button>
                                                            <?php } else { ?>
                                                            <button class="btn btn-sm btn-success" style="border:0px; width: 100%;padding: 10px;margin-top: 5px;">
                                                              Played
                                                            </button>
                                                            <?php } ?>
                                                          </div>

                                                          <!-- <div id="bet_now" class="col-md-2">
                                                            <button onclick="$('#bet_options').show ( 200 ); $('#bet_now').hide ( 200 )"
                                                              class="btn btn-primary w-100" style="height: 100%">
                                                              Bet Now
                                                            </button>
                                                          </div> -->

                                                        </div>
                                                      </div>

                                                    </div>

                                                    <div class="card card-body" style="display:none" id="bet_options_<?php print ( $x['id'] )?>">
                                                      <h5>
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                          class="icon icon-tabler icon-tabler-adjustments-horizontal" width="44" height="44"
                                                          viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                                          stroke-linecap="round" stroke-linejoin="round">
                                                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                          <path d="M14 6m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                          <path d="M4 6l8 0" />
                                                          <path d="M16 6l4 0" />
                                                          <path d="M8 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                          <path d="M4 12l2 0" />
                                                          <path d="M10 12l10 0" />
                                                          <path d="M17 18m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                          <path d="M4 18l11 0" />
                                                          <path d="M19 18l1 0" />
                                                        </svg> Game Settings
                                                      </h5>
                                                      <form>
                                                        <input type="hidden" id="teams_<?php print ( $x['id'] ) ?>" value="<?php print ($x['team_a'])?>,<?php print ($x['team_b'])?>">
                                                        <div class="form-floating" style="margin-bottom: 10px;">
                                                          <select id="teams_bet_type_<?php print( $x['id'] ) ?>" onchange="BetOptions ( this.value, <?php print ( $x['id'] ) ?> ); "
                                                            class="form-select"
                                                            aria-label="Floating label select example">
                                                            <option selected disabled>-- Select --</option>
                                                            <option value="1">Win (extra time or penalty)</option>
                                                            <option value="2">Straight Win</option>
                                                            <option value="3">Both teams to score</option>
                                                            <option value="4">Draw</option>
                                                          </select>
                                                          <label for="floatingSelect">Bet Type</label>
                                                        </div>

                                                        <div id="bet_teams_<?php print ( $x['id'] ) ?>" class="form-floating" style="margin-bottom: 10px;display: block">
                                                          <select id="teams_value_<?php print ( $x['id'] ) ?>" onchange="Odds ( this.value, undefined, <?php print ( $x['id'] ) ?> )" class="form-select" aria-label="Floating label select example" style="text-transform: capitalize">
                                                            <option selected value="0">-- Select --</option>
                                                            <option value="<?php print ( $x['team_a'] ) ?>, <?php print ( $a_odds ) ?>"><?php print ( $x['team_a'] ) ?>: <?php print ( $a_odds ) ?> odds</option>
                                                            <option value="<?php print ( $x['team_b'] ) ?>, <?php print ( $b_odds ) ?>"><?php print ( $x['team_b'] ) ?>: <?php print ( $b_odds) ?> odds</option>
                                                          </select>
                                                          <label for="floatingSelect">Select Team</label>
                                                        </div>

                                                        <!-- <div class="form-floating" style="margin-bottom: 10px;">
                                                          <input type="email" class="form-control" id="floating-input" autocomplete="off">
                                                          <label for="floatingSelect">Enter Amount (&#8358; 50 min.)</label>
                                                        </div> -->

                                                        <div class="form-floating" style="margin-bottom: 10px;margin-top: 10px;"
                                                          align="center">
                                                          <button onclick="AddGame ( <?php print ( $x['id'] ) ?> )" type="button" class="btn btn-md btn-success"
                                                            style="width: 70%" id="add_odds_<?php print( $x['id'] ) ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                              class="icon icon-tabler icon-tabler-device-gamepad-2" width="44" height="44"
                                                              viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                                              stroke-linecap="round" stroke-linejoin="round">
                                                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                              <path
                                                                d="M12 5h3.5a5 5 0 0 1 0 10h-5.5l-4.015 4.227a2.3 2.3 0 0 1 -3.923 -2.035l1.634 -8.173a5 5 0 0 1 4.904 -4.019h3.4z" />
                                                              <path d="M14 15l4.07 4.284a2.3 2.3 0 0 0 3.925 -2.023l-1.6 -8.232" />
                                                              <path d="M8 9v2" />
                                                              <path d="M7 10h2" />
                                                              <path d="M14 10h2" />
                                                            </svg> Add (<span id="odd_cnt_<?php print ( $x['id']) ?>">0</span>&nbsp;odds)
                                                          </button>
                                                          <button onclick="$('#bet_options_<?php print ( $x['id'] ) ?>').hide ( 200 ); $('#bet_now_<?php print ( $x['id'] )?>').show ( 200 )"
                                                            type="button" class="btn btn-md btn-danger" style="width: 20%">
                                                            Hide
                                                          </button>
                                                        </div>
                                                      </form>
                                                    </div>

                                                  </div>
                                          <?php
                                              }
                                          ?>
                                      </div>
                                      <div class="col-md-4"
                                        style="border-left: var(--tblr-border-width) var(--tblr-border-style) var(--tblr-border-color-translucent);">
                                        <div class="table-responsive">

                                          <div class="top_stand bg-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                              class="icon icon-tabler icon-tabler-ball-volleyball" width="44" height="44"
                                              viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                              stroke-linecap="round" stroke-linejoin="round">
                                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                              <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                              <path d="M12 12a8 8 0 0 0 8 4" />
                                              <path d="M7.5 13.5a12 12 0 0 0 8.5 6.5" />
                                              <path d="M12 12a8 8 0 0 0 -7.464 4.928" />
                                              <path d="M12.951 7.353a12 12 0 0 0 -9.88 4.111" />
                                              <path d="M12 12a8 8 0 0 0 -.536 -8.928" />
                                              <path d="M15.549 15.147a12 12 0 0 0 1.38 -10.611" />
                                            </svg> TOP TEAMS
                                          </div>
                                          <?php
                                              require ( "teams.php" );
                                          ?>
                                        </div>
                                      </div>
                                  </div>
                                </div>
                            </div>
                    <?php
                        }
                     ?>
                  </div>

                  <div class="tab-pane hide" id="tabs-profile-1" role="tabpanel">

                    <div id="aca_one" style="display:block">
                      <div class="page-pretitle">
                        Tick as much as you can do!
                      </div>
                      <h2 class="page-title" style="margin-bottom: 10px;">
                        Select Course
                      </h2>

                      <div class="mb-3">
                        <?php
                            while ( $x = mysqli_fetch_array ( $courses[1] ) )
                            {
                              $value  = "course = '$x[name]'";
                              $exist    = DB ( 2, 'questions', $value, "", $conn, "" );
                              $div = '<div class="badge bg-lime-lt">Available</div>';
                              if ( $exist[1] )
                              {
                                  $hide = "block";
                              }
                              else
                              {
                                  $hide = "none";
                              }
                        ?>
                              <div class="mb-1">
                                <label style="display:<?php print ( $hide ) ?>" class="form-selectgroup-item flex-fill">
                                  <input onclick="SetInput ( '<?php print ( $x['short'] ) ?>', 1, COURSE )" type="checkbox"
                                    class="form-selectgroup-input">
                                  <div class="form-selectgroup-label d-flex align-items-center p-3">
                                    <div class="me-3">
                                      <span class="form-selectgroup-check"></span>
                                    </div>
                                    <div class="form-selectgroup-label-content d-flex align-items-center">
                                      <span class="avatar avatar-x2 mb-1 rounded">
                                          <?php
                                              print ( $x['short'] )
                                          ?>
                                      </span>
                                      <div style="padding-left: 10px;">
                                        <div class="font-weight-medium" style="text-transform: uppercase"><?php
                                              print ( $x['name'] )
                                          ?></div>
                                        <?php
                                            print ( $div );
                                        ?>
                                      </div>
                                    </div>
                                  </div>
                                </label>
                              </div>
                        <?php
                            }
                        ?>

                        <div align="center" style="margin-top: 10px;">
                          <button onclick="Stage ( 1 )" class="btn btn-md btn-primary">
                            Continue <svg xmlns="http://www.w3.org/2000/svg"
                              class="icon icon-tabler icon-tabler-arrow-badge-right" width="44" height="44"
                              viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                              stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
                            </svg>
                          </button>
                        </div>
                      </div>
                    </div>

                    <div id="aca_two" style="display:none">
                      <div class="page-pretitle ">
                        Select difficulty level
                      </div>
                      <h2 class="page-title" style="margin-bottom: 10px;">
                        Set Level
                      </h2>

                      <div class="mb-3">
                        <?php
                          while ( $x = mysqli_fetch_array ( $time[1] ) )
                          {
                        ?>
                              <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column" style="margin-top: 10px;">
                                <label class="form-selectgroup-item flex-fill">
                                  <input onclick="LEVEL = [];SetInput ( <?php print ( $x['level'] ) ?>, 2, LEVEL )" type="radio" name="form-payment" value="visa"
                                    class="form-selectgroup-input">
                                  <div class="form-selectgroup-label d-flex align-items-center p-3">
                                    <div class="me-3">
                                      <span class="form-selectgroup-check"></span>
                                    </div>
                                    <div class="text-secondary">
                                      <div style="margin-bottom: 5px; color: #fff">
                                        Level <?php
                                            print ( $x['level'] )
                                        ?>
                                      </div>
                                      <div>
                                        <span class="badge badge-outline text-azure">
                                          Odds: <?php
                                              print ( $x['odds'] )
                                          ?>
                                        </span>
                                        <span class="badge badge-outline text-danger">
                                          Time: <?php
                                              print ( $x['time'] )
                                          ?> sec per question
                                        </span>

                                        <div style="margin-top: 10px; font-size: 12px; color: #fff">
                                          You win if you answer <?php print ( $x['level'] ) ?>% of the questions correctly
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </label>
                              </div>
                        <?php
                          }
                        ?>

                        <div align="center" style="margin-top: 10px;">
                          <button onclick="$('#aca_one').show ( 200 );$('#aca_two').hide ( 200 )"
                            class="btn btn-md btn-danger">
                            <svg xmlns="http://www.w3.org/2000/svg"
                              class="icon icon-tabler icon-tabler-arrow-badge-left" width="44" height="44"
                              viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                              stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M11 17h6l-4 -5l4 -5h-6l-4 5z" />
                            </svg> Back
                          </button>
                          <button onclick="Stage ( 2 )" class="btn btn-md btn-primary">
                            Continue <svg xmlns="http://www.w3.org/2000/svg"
                              class="icon icon-tabler icon-tabler-arrow-badge-right" width="44" height="44"
                              viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                              stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
                            </svg>
                          </button>
                        </div>
                      </div>

                    </div>

                    <div id="aca_thr" style="display:none">
                      <div class="page-pretitle ">
                        Play your bet
                      </div>
                      <h2 class="page-title" style="margin-bottom: 10px;">
                        Finally!
                      </h2>
                      <div class="card card-body">
                        <h5>
                          Please take note that:
                        </h5>
                        <ol style="line-height: 25px;">
                          <li>
                            The timing is per question
                          </li>
                          <li>
                            Once the time is up, you cant go back to the question again
                          </li>
                          <li>
                            You have two ( 2 ) life lines that you can use once per game. The life lines are listed
                            below:
                            <ul>
                              <li>
                                <span class="badge bg-success-lt">Fifty Fifty</span>: As the name implies, this life
                                line
                                removes some options that are wrong, leaving you with one correct option and one wrong option,
                                giving you a short list of options to pick from
                              </li>
                              <li>
                                <span class="badge bg-success-lt">Freeze</span>: This will pause the time for some minutes usually within 1 to 4
                                minutes giving you some time to look for the answer
                              </li>
                              <!-- <li>
                                <span class="badge bg-success-lt">Time Machine</span>: This life line will return you
                                back to at most three ( 3 ) of the questions you did not answer correctly, giving you
                                another chance to try them again
                              </li> -->
                            </ul>
                          </li>
                          <li>
                              Take note that once the game starts, you can not quit and submission will be done immediately after the last question
                          </li>
                          <li>
                            The word "Odds" is simply the number of times the amount you bet on will be multiplied if
                            you win
                          </li>
                          <li>
                              Bet responsibly
                          </li>
                          <li>
                            Goodluck
                          </li>
                        </ol>
                        <form>
                          <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="bet_amount" autocomplete="off">
                            <label for="floating-input">Enter Amount ( &#8358; 50 min. )</label>
                          </div>
                          <div align="center" style="margin-top: 10px;">
                            <button type="button" id="quiz_back" onclick="$('#aca_two').show ( 200 );$('#aca_thr').hide ( 200 )"
                              class="btn btn-md btn-danger">
                              <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-arrow-badge-left" width="44" height="44"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M11 17h6l-4 -5l4 -5h-6l-4 5z" />
                              </svg> Back
                            </button>
                            <button type="button" id="hide_quiz" class="btn btn-md btn-primary" style="display: none;" disabled="">
                              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                              Loading...
                            </button>
                            <button type="button" id="show_quiz" onclick="Play ( $('#bet_amount').val ( ), 'show_quiz', 'hide_quiz' );" class="btn btn-md btn-primary">
                              Continue <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-arrow-badge-right" width="44" height="44"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
                              </svg>
                            </button>
                            <button type="button" id="show_quiz" onclick="Play ( $('#bet_amount').val ( ), 'show_quiz', 'hide_quiz' );" class="btn btn-md btn-primary">
                              ss
                            </button>
                          </div>
                        </form>

                      </div>
                    </div>

                    <div id="aca_for" style="display:none">
                      <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-6">
                          <div class="page-pretitle ">
                            You have <span id="quest_count"></span> questions to answer in this game
                          </div>
                          <h2 class="page-title" style="margin-bottom: 10px;">
                            <time id="game_time">00:00</time>
                          </h2>
                        </div>
                        <div class="col-md-6">
                          <div class="page-pretitle ">
                            Life Lines
                          </div>
                          <button id="fifty" class="btn btn-md" onclick="LifeLine ( 1, this.id )">
                            50 - 50
                          </button>
                          <button id="freezer" class="btn btn-md" onclick="LifeLine ( 2, this.id )">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-wand" width="44"
                              height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                              stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M6 21l15 -15l-3 -3l-15 15l3 3" />
                              <path d="M15 6l3 3" />
                              <path d="M9 3a2 2 0 0 0 2 2a2 2 0 0 0 -2 2a2 2 0 0 0 -2 -2a2 2 0 0 0 2 -2" />
                              <path d="M19 13a2 2 0 0 0 2 2a2 2 0 0 0 -2 2a2 2 0 0 0 -2 -2a2 2 0 0 0 2 -2" />
                            </svg>
                          </button>
                          <!-- <button class="btn btn-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alarm-filled"
                              width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                              fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path
                                d="M16 6.072a8 8 0 1 1 -11.995 7.213l-.005 -.285l.005 -.285a8 8 0 0 1 11.995 -6.643zm-4 2.928a1 1 0 0 0 -1 1v3l.007 .117a1 1 0 0 0 .993 .883h2l.117 -.007a1 1 0 0 0 .883 -.993l-.007 -.117a1 1 0 0 0 -.993 -.883h-1v-2l-.007 -.117a1 1 0 0 0 -.993 -.883z"
                                stroke-width="0" fill="currentColor" />
                              <path
                                d="M6.412 3.191a1 1 0 0 1 1.273 1.539l-.097 .08l-2.75 2a1 1 0 0 1 -1.273 -1.54l.097 -.08l2.75 -2z"
                                stroke-width="0" fill="currentColor" />
                              <path
                                d="M16.191 3.412a1 1 0 0 1 1.291 -.288l.106 .067l2.75 2a1 1 0 0 1 -1.07 1.685l-.106 -.067l-2.75 -2a1 1 0 0 1 -.22 -1.397z"
                                stroke-width="0" fill="currentColor" />
                            </svg>
                          </button> -->
                        </div>
                      </div>

                      <div align="center" id="question">
                      </div>
                      <!-- <button onclick="Mark ( )">tt</button> -->
                      <div align="left" style="margin-top: 10px;"><button class="btn btn-md btn-primary" id="next_quest" onclick="Next ( )">Next</button></div>
                    </div>

                    <div id="aca_fiv" style="display:none" align="center">
                        <span style="width: 40px; height: 40px;" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <div class="text-secondary">
                            Fetching Result...
                        </div>
                    </div>

                    <div id="aca_six" style="display:none" align="center">
                      
                      <div class="modal-content" id="aca_six_true" style="display: none">
                        <!-- <div class="modal-status bg-success"></div> -->
                        <div class="modal-body text-center py-4">
                          <!-- Download SVG icon from http://tabler-icons.io/i/circle-check -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
                          <h3>
                            You win!!!
                          </h3>
                          <div class="text-secondary" id="aca_six_true_msg"></div>
                        </div>
                        <div class="modal-footer">
                          <div class="w-100">
                            <div class="row">
                              <div class="col"><button onclick="Reload ( )" class="btn btn-success w-100" data-bs-dismiss="modal">
                                  Finish
                              </button></div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="modal-content" id="aca_six_false">
                        <div class="modal-body text-center py-4">
                          <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" /><path d="M12 9v4" /><path d="M12 17h.01" /></svg>
                          <h3>You Lose!!</h3>
                          <div class="text-secondary" id="aca_six_false_msg"></div>
                        </div>
                        <div class="modal-footer">
                          <div class="w-100">
                            <div class="row">
                              <div class="col"><button onclick="Reload ( )" class="btn btn-danger w-100" data-bs-dismiss="modal">
                                  Finish
                              </button></div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                    
                    <div id="aca_one_error" class="alert alert-danger" role="alert" style="display: none">
                      <div class="d-flex">
                        <div>
                          <!-- Download SVG icon from http://tabler-icons.io/i/alert-circle -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                            <path d="M12 8v4"></path>
                            <path d="M12 16h.01"></path>
                          </svg>
                        </div>
                        <div>
                            Please add more options to the list
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <footer class=" footer footer-transparent d-print-none">
          <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
              <div class="col-lg-auto ms-lg-auto">

              </div>
              <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item">
                    Copyright &copy; <?php
                        print ( date ( 'Y', time ( ) ) );
                    ?>
                    <a href=".." class="link-secondary">Skoolbet</a>.
                    All rights reserved.
                  </li>
                  <!-- <li class="list-inline-item">
                    <a href="../changelog.html" class="link-secondary" rel="noopener">
                      v1.0.0-beta20
                    </a>
                  </li> -->
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    
    <!-- Modals -->
    <div class="offcanvas offcanvas-bottom hide" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel"
      aria-modal="true" role="dialog" style="height: 92%; overflow-y: auto;">
      
      <div id="my_games_played" style="display:none">
        <div class="offcanvas-header">
          <h2 class="offcanvas-title" id="offcanvasBottomLabel">

          </h2>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <div class="page-pretitle ">
              <?php
                  $count    = mysqli_num_rows ( $total_games[1] );
                  if ( $count )
                  {
                      print (
                        'You played ' . $count . ' game(s)'
                      );
                  }
                  else
                  {
                      print (
                          'No games played yet'
                      );
                  }
              ?>
          </div>
          <h2 class="page-title" style="margin-bottom: 10px;">
            My Games
          </h2>

          <!-- <div class="input-icon mb-3">
            <input type="text" value="" class="form-control" placeholder="Search…">
            <span class="input-icon-addon">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                <path d="M21 21l-6 -6"></path>
              </svg>
            </span>
          </div> -->

          <div class="card">
            <div class="table-responsive">
              <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                  <tr>
                    <th>Status</th>
                    <th>Team A</th>
                    <th>Team B</th>
                    <th>Slip No</th>
                    <th>Odds</th>
                    <th>Schl.</th>
                    <th>Cup</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                      while ( $x = mysqli_fetch_array ( $total_games[1] ) )
                      {
                        $value  = "id = $x[gid]";
                        $sch    = DB ( 3, 'new_games', $value, "", $conn, "" );
                        $sch    = mysqli_fetch_array ( $sch[1] );

                        $value  = "id = $x[gid]";
                        $sch    = DB ( 3, 'new_games', $value, "", $conn, "" );
                        $sch    = mysqli_fetch_array ( $sch[1] );

                        $value  = "id = $sch[school]";
                        $sch_name    = DB ( 3, 'schools', $value, "", $conn, "" );
                        $sch_name    = mysqli_fetch_array ( $sch_name[1] );

                        $value  = "id = $sch[title]";
                        $titles    = DB ( 3, 'titles', $value, "", $conn, "" );
                        $titles    = mysqli_fetch_array ( $titles[1] );

                        if ( $sch['a_logo'] == 'undefined' )
                        {
                            $a_logo = '../static/teams/team_a.png)';
                        }
                        else
                        {
                            $a_logo = $sch['a_logo'];
                        }

                        if ( $sch['b_logo'] == 'undefined' )
                        {
                            $b_logo = '../static/teams/team_b.jpeg)';
                        }
                        else
                        {
                            $b_logo = $sch['b_logo'];
                        }
                  ?>
                        <tr>
                          <td>
                              <?php
                                  if ( $x['status'] == -1 )
                                  {
                                      print (
                                          '<span class="badge bg-warning me-1"></span> Pending'
                                      );
                                  }
                                  else if ( $x['status'] == 1 )
                                  {
                                      print (
                                          '<span class="badge bg-success me-1"></span> WIN'
                                      );
                                  }
                                  else if ( $x['status'] == 2 )
                                  {
                                      print (
                                          '<span class="badge bg-danger me-1"></span> LOSS'
                                      );
                                  }

                                  print (
                                    '<br><span class="badge bg-lime-lt" style="font-size: 12px;">
                                        '. date ( 'M d, Y', $x['date'] ) . '|' . date ( 'h:mA', $x['time'] ) .'
                                    </span>'
                                );
                              ?>
                          </td>
                          <td style="text-transform: capitalize">
                          <span class="avatar" style="width: 30px; height: 20px;background-image: url(<?php print ( $a_logo ) ?>"></span>
                            <?php
                                print ( $sch['team_a'] )
                            ?>
                          </td>
                          <td style="text-transform: capitalize">
                          <span class="avatar" style="width: 30px; height: 20px;background-image: url(<?php print ( $b_logo ) ?>"></span>
                            <?php
                                print ( $sch['team_b'] )
                            ?>
                          </td>
                          <td>
                              <?php
                                  print ( $x['slip_no'] );
                              ?>
                          </td>
                          <td>
                              <?php
                                  print ( $x['odd'] );
                              ?>
                          </td>
                          <td>
                              <?php
                                  print ( $sch_name['short'] );
                              ?>
                          </td>
                          <td>
                              <?php
                                  print ( $titles['title'] );
                              ?>
                          </td>
                        </tr>
                  <?php
                      }
                  ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="mt-3">
            <button class="btn btn-primary" type="button" data-bs-dismiss="offcanvas">
              Close
            </button>
          </div>
        </div>
      </div>

      <div id="fund" style="display: none;">
        <div class="offcanvas-header">
          <h2 class="offcanvas-title" id="offcanvasBottomLabel">

          </h2>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <div class="page-pretitle ">
            Add money to your account
          </div>
          <h2 class="page-title" style="margin-bottom: 10px;">
            Fund Account
          </h2>

          <div class="row">
            <div class="col-md-4">
              <form id="wallet">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="amount" autocomplete="off" value="0">
                  <label for="floating-input">(&#8358;) Enter Amount</label>
                </div>
                <button type="button" id="hide_pay" class="btn btn-primary w-100" style="display: none;" disabled="">
                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  Loading...
                </button>
                <button id="show_pay" type="button" onclick="Pay ( '<?php print ( $bio['email'] ) ?>' )" class="btn btn-primary ms-auto" style="width: 100%">
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
                          while ( $x = mysqli_fetch_array ( $payment[1] ) )
                          {
                      ?>
                            <tr>
                              <td>
                                  <?php
                                      print (
                                          date ( 'M d, Y', $x['time'] ) . ' | ' . date ( 'h:iA', $x['time'] )
                                      );
                                  ?>
                              </td>
                              <td>
                                &#8358; <?php print ( number_format ( $x['amount'] ) ) ?>
                              </td>
                              <td class="text-secondary">
                                <span class="badge bg-lime text-lime-fg">
                                  Successfull
                                </span>
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

      <div id="withdraw" style="display:none">
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
                <button id="show_with" type="button" onclick="req = DataObject ( 'withdraw' ); Withdraw ( req, 'show_with', 'hide_with', 1, 'withdraw' )" class="btn btn-primary ms-auto" style="width: 100%">
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

      <div id="profile" style="display:none">
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
              <form id="users">
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
                  onclick="req = DataObject ( 'users' ); Profile ( req, 'show_prof', 'hide_prof', 1, 'users' )"
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
    
    <div class="card card-body bet_cont" id="bet_cont" style="display: none;">
      <div class="offcanvas-header">
        <h2 class="offcanvas-title" id="offcanvasTopLabel">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list-tree" width="44" height="44"
            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round"
            stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M9 6h11" />
            <path d="M12 12h8" />
            <path d="M15 18h5" />
            <path d="M5 6v.01" />
            <path d="M8 12v.01" />
            <path d="M11 18v.01" />
          </svg>Bet Slip
        </h2>
        <button type="button" onclick="$('#bet_cont').hide ( 200 )" class="btn-close text-reset"
          aria-label="Close"></button>
      </div>
      <div style="margin-top: 10px;">
        <form id="game_form">
          <input type="hidden" id="options_bet">
          <input type="hidden" id="bet_team">
        </form>
        <div id="bet_loader" style="display:none">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading...
        </div>
        <!-- <button onclick="SaveGame ( 'innerForm' )" type="button" class="btn btn-secondary btn-sm">try</button> -->
        <div class="table-responsive" id="bet_slip">
          <div class="alert alert-warning" role="alert">
            <div class="d-flex">
              <div>
                <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                  viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                  stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path
                    d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z">
                  </path>
                  <path d="M12 9v4"></path>
                  <path d="M12 17h.01"></path>
                </svg>
              </div>
              <div>
                Your bet slip is empty
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="error_cont" class="alert alert-important alert-danger alert-dismissible" role="alert"
    style="position: fixed; top: 0%; right: 0px; display:none;z-index: 3; border-radius: 0px;">
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
    </div>
    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
  </div>
  
   <script> 
        document.onreadystatechange = function () {
          if (document.readyState !== "complete") {
            document.querySelector(
              "body").style.visibility = "hidden";
            document.querySelector(
              "#loader").style.visibility = "visible";
          } else {
            document.querySelector(
              "#loader").style.display = "none";
            document.querySelector(
              "body").style.visibility = "visible";
          }
        };
   </script> 
  <!-- Libs JS -->
  <!-- Tabler Core -->
   
  <script src="../dist/js/tabler.min.js?1692870487" defer></script>
  <script src="../dist/js/demo.min.js?1692870487" defer></script>
  <script src="../dist/js/backend.js" defer></script>
  <script src="../dist/js/index.js" defer></script>
  <script src="../dist/js/jone.js" defer></script>
  <script src="https://js.paystack.co/v2/inline.js"></script>
</body>
</html>