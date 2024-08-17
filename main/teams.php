<?php
    // $school = $bio['school'];
    $value  = "1";
    $season = DB ( 3, 'titles', $value, "", $conn, "" );
    $xseason = [];
    while ( $x = mysqli_fetch_array ( $season[1] ) )
    {
        $xseason[] = $x['id'];
    }

    array_unique ( $xseason );
    sort ( $xseason );
?>
<div>
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
                    $value  = "title = $xseason[$i] and season = 0 and school = $school";
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
                        $value  = "winner = '$win[$m]' and season = 0";
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