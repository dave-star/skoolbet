<div class="card">
    <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a href="#tabs-home-3" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-link" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M20.085 11.085l-8.085 -8.085l-9 9h2v7a2 2 0 0 0 2 2h4.5" />
                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 1.807 1.143" />
                    <path d="M21 21m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M21 16m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M16 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M21 16l-5 3l5 2" />
                </svg>
                &nbsp;Links
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="#tabs-profile-3" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-xbox" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                    <path d="M6.5 5c7.72 2.266 10.037 7.597 12.5 12.5" />
                    <path d="M17.5 5c-7.72 2.266 -10.037 7.597 -12.5 12.5" />
                </svg>
                &nbsp;Games
            </a>
        </li>
    </ul>
    </div>
    <div class="card-body">
    <div class="tab-content">
        <div class="tab-pane active show" id="tabs-home-3" role="tabpanel">
            <h4>
                Get link to game fixtures
            </h4>
            <div>
                <?php
                    $value  = "sid = $main_id and season = 0 and school = $_SESSION[school]";
                    $games = DB ( 3, 'new_games', $value, "", $conn, "" );
                    $row = mysqli_num_rows ( $games[1] );
                    if ( $row )
                    {
                        $g = [];
                        while ( $x = mysqli_fetch_array ( $games[1] ) )
                        {
                            $g[] = $x['title'];
                        }

                        $g = array_unique ( $g );
                        sort ( $g );
                        for ( $x = 0; $x < count ( $g ) ; $x ++ )
                        {
                            $value  = "id = $g[$x]";
                            $title = DB ( 3, 'titles', $value, "", $conn, "" );
                            $title = mysqli_fetch_array ( $title[1] );
                            
                            print (
                                '<div class="form-floating mb-3">
                                <input type="email" class="form-control" id="floating-input" value="'. GetLink ( $_SESSION['id'], $title['id'] ) .'" autocomplete="off">
                                <label for="floating-input">'. $title['title'] .' Link</label>
                                </div>'
                            );
                        }
                    }
                    else
                    {
                        print (
                            '<div class="alert alert-warning" role="alert">
                                <div class="d-flex">
                                    <div>
                                    <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z"></path><path d="M12 9v4"></path><path d="M12 17h.01"></path></svg>
                                    </div>
                                    <div>
                                    Uh oh, no links found
                                    </div>
                                </div>
                            </div>'
                        );
                    }
                ?>
            </div>
        </div>
        <div class="tab-pane" id="tabs-profile-3" role="tabpanel">
            <h4>
                Start Games
            </h4>
            <div>
                <?php
                    $value  = "sid = $main_id and season = 0 and school = $_SESSION[school] and status != 2";
                    $games = DB ( 3, 'new_games', $value, "", $conn, "" );
                    $row = mysqli_num_rows ( $games[1] );
                    if ( $row )
                    {
                        while ( $x = mysqli_fetch_array ( $games[1] ) )
                        {
                            $value  = "id = $x[title]";
                            $title = DB ( 3, 'titles', $value, "", $conn, "" );
                            $title = mysqli_fetch_array ( $title[1] );

                            $value  = "id = $x[stage]";
                            $stage = DB ( 3, 'stages', $value, "", $conn, "" );
                            $stage = mysqli_fetch_array ( $stage[1] );

                            $active = "none";
                            if ( $x['status'] == 1 )
                            {
                                $active = "block";
                            }

                            // if ( $x['status'] != 2 )
                            {
            ?>
                                <div class="accordion" id="accordion-example_play_<?php print ($x['id'])?>" style="margin-bottom: 10px;">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-1_play_<?php print ($x['id'])?>">
                                            <button class="accordion-button " type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse-1_play_<?php print ($x['id'])?>"
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
                                                <div style="position: absolute; top: 0px; right: 5px;font-size: 12px;">
                                                    <span class="badge bg-lime-lt"><?php
                                                        print ( $title['title'] )
                                                    ?></span>
                                                    <span class="badge bg-red-lt"><?php
                                                        print ( $stage['title'] )
                                                    ?></span>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse-1_play_<?php print ($x['id'])?>" class="accordion-collapse collapse hide"
                                            data-bs-parent="#accordion-example">
                                            <div class="accordion-body pt-0">
                                                <?php
                                                    $kick = 'none';
                                                    $final = 'none';
                                                    if ( $x['status'] == 0 )
                                                    {
                                                        $kick = 'block';
                                                    }
                                                    else if ( $x['status'] == 1 )
                                                    {
                                                        $final = 'block';
                                                    }
                                                    
                                                ?>
                                                <button type="button" id="hide_fixture_<?php print( $x['id'] ) ?>" class="btn btn-sm" style="display: none;border: 0px;" disabled="">
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    Loading...
                                                </button>
                                                <button style="display: <?php print ( $kick )?>" id="kick_button_<?php print ( $x['id'] ) ?>" onclick="KickOff ( <?php print ( $x['id'] ) ?> )" class="btn btn-sm btn-success">
                                                    Kick Off
                                                </button>
                                                <button onclick="req = DataObject ( 'kickoff_<?php print ( $x['id'] ) ?>' ); FinishGame ( req, <?php print ( $x['id'] ) ?> )" style="display: <?php print ( $final )?>" id="final_button_<?php print ( $x['id'] ) ?>" onclick="KickOff ( <?php print ( $x['id'] ) ?> )" class="btn btn-sm btn-danger">
                                                    Final Whistle
                                                </button>
                                                <!-- <button onclick="req = DataObject ( 'kickoff_<?php print ( $x['id'] ) ?>' ); FinishGame ( req, <?php print ( $x['id'] ) ?> )" style="display: block" onclick="KickOff ( <?php print ( $x['id'] ) ?> )" class="btn btn-sm btn-danger">
                                                    jj
                                                </button> -->
                                                <form id="kickoff_<?php print ( $x['id'] ) ?>" style="display: <?php print ( $active ) ?>">
                                                    <div style="margin-top: 10px;margin-bottom: 10px;font-size: 12px">
                                                        Score Board
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3">
                                                                <input type="number" class="form-control" id="score_a">
                                                                <label style="text-transform: capitalize" for="floating-input"><?php print ( $x['team_a'] ) ?> score</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3">
                                                                <input type="number" class="form-control" id="score_b">
                                                                <label style="text-transform: capitalize" for="floating-input"><?php print ( $x['team_b'] ) ?> score</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-label" style="font-size: 12px">Did you play <b>Extra Time</b>?</div>
                                                        <div>
                                                            <input type="hidden" id="extra_time_<?php print ( $x['id'] ) ?>" value = "0">
                                                            <label class="form-check form-check-inline" onclick="ExtraTime ( 1, <?php print ( $x['id'] ) ?> )">
                                                                <input class="form-check-input" type="radio" name="radios-inline">
                                                                <span class="form-check-label">Yes</span>
                                                            </label>
                                                            <label class="form-check form-check-inline" onclick="ExtraTime ( 0, <?php print ( $x['id'] ) ?> )">
                                                                <input class="form-check-input" type="radio" name="radios-inline" checked="">
                                                                <span class="form-check-label">No</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div style="margin-top: 10px;margin-bottom: 10px;font-size: 12px">
                                                        Penalty Score Board
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3">
                                                                <input value="0" type="number" class="form-control" id="score_a_pen">
                                                                <label style="text-transform: capitalize" for="floating-input"><?php print ( $x['team_a'] ) ?> score</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating mb-3">
                                                                <input value="0" type="number" class="form-control" id="score_b_pen">
                                                                <label style="text-transform: capitalize" for="floating-input"><?php print ( $x['team_b'] ) ?> score</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
            <?
                            }
                        }
                    }
                    else
                    {
                        print (
                            '<div class="alert alert-warning" role="alert">
                                <div class="d-flex">
                                    <div>
                                    <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z"></path><path d="M12 9v4"></path><path d="M12 17h.01"></path></svg>
                                    </div>
                                    <div>
                                    Uh oh, no games found
                                    </div>
                                </div>
                            </div>'
                        );
                    }
                ?>
            </div>
        </div>
    </div>
    </div>
</div>