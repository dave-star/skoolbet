<?php
    $value  = "1";
    $titles = DB ( 3, 'titles', $value, "", $conn, "" );
    $value  = "1";
    $xstage = DB ( 3, 'stages', $value, "", $conn, "" );
?>
<form id="new_games_<?php print ( $x['id'] ) ?>" enctype="multipart/form-data" style="border-top: 1px solid grey; margin-top: 10px;padding-top: 10px;">
    <input type="hidden" value="<?php print ( $bio['school'] ) ?>" id="school">
    <div class="form-floating mb-3">
        <select onchange="Custom ( this.value )" class="form-select"
            id="title" aria-label="Floating label select example">
            <option disabled>-- Title --</option>
            <?php
                mysqli_data_seek ( $titles[i] );
                while ( $m = mysqli_fetch_array ( $titles[1] ) )
                {
                    if ( $m['id'] == $x['title'] )
                    {
                        print (
                            "<option value='$m[id]' selected>$m[title]</option>"
                        );
                    }
                    else
                    {
                        print (
                            "<option value='$m[id]'>$m[title]</option>"
                        );
                    }
                }
            ?>
            <!-- <option value="sk_custom">Others</option> -->
        </select>
        <label for="floating-input">
            Game Title
        </label>
    </div>
    <div class="form-floating mb-3">
        <select id="stage" class="form-select" aria-label="Floating label select example">
            <option disabled>-- Stage --</option>
            <?php
                while ( $m = mysqli_fetch_array ( $xstage[1] ) )
                {
                    if ( $m['id'] == $x['title'] )
                    {
                        print (
                            "<option value='$m[id]' selected>$m[title]</option>"
                        );
                    }
                    else
                    {
                        print (
                            "<option value='$m[id]'>$m[title]</option>"
                        );
                    }
                }
            ?>
        </select>
        <label for="floating-input">
            Game Stage
        </label>
    </div>
    <div style="padding: 5px;">
        <div class="row">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input value="<?php print ( $x['team_a'] ) ?>" id="team_a" class="form-control" autocomplete="off">
                    <label for="floating-input">
                        Team A ( Name )
                    </label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input id="team_b" value="<?php print ( $x['team_b'] ) ?>" class="form-control" autocomplete="off">
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
                            for ( $o = 0; $o < count ( $odds ); $o ++ )
                            {
                                if ( $odds_id[$o] == $x['a_odds'] )
                                {
                                    print (
                                        "<option selected value='$odds_id[$o]'>$odds[$o]</option>"
                                    );
                                }
                                else
                                {
                                    print (
                                        "<option value='$odds_id[$o]'>$odds[$o]</option>"
                                    );
                                }
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
                            for ( $o = 0; $o < count ( $odds ); $o ++ )
                            {
                                if ( $odds_id[$o] == $x['b_odds'] )
                                {
                                    print (
                                        "<option selected value='$odds_id[$o]'>$odds[$o]</option>"
                                    );
                                }
                                else
                                {
                                    print (
                                        "<option value='$odds_id[$o]'>$odds[$o]</option>"
                                    );
                                }
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
                        value = "<?php print ( $x['game_date'] )?>">
                    <label for="floating-input">
                        Date
                    </label>
                </div>
            </div>
            <div class="col-md-6">
                <input type="hidden" value="<?php print ( $x['id'] ) ?>" id="id">
                <div class="form-floating mb-3">
                    <input id="game_time" type="time" class="form-control"
                    value = "<?php print ( $x['game_time'] )?>">
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
    <button type="button" id="hide_update_<?php print ( $x['id'] ) ?>" class="btn btn-md btn-danger w-100" style="display: none;" disabled="">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Loading...
    </button>
    <!-- <button type="button" onclick="req = DataObject ( 'new_games_' + <?php print ( $x['id'] ) ?> ); UpdateGame ( req, 'show_update_<?php print ( $x['id'] ) ?>', 'hide_update_<?php print ( $x['id'] ) ?>', -10, 'new_games' )">HH</button> -->
    <button type="button" id="show_update_<?php print ( $x['id'] ) ?>" onclick="req = DataObject ( 'new_games_' + <?php print ( $x['id'] ) ?> ); UpdateGame ( req, 'show_update_<?php print ( $x['id'] ) ?>', 'hide_update_<?php print ( $x['id'] ) ?>', -10, 'new_games' )" class="btn btn-md btn-danger w-100">
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
        </svg> Update
    </button>
</form>