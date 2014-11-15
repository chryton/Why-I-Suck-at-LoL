<?php 

class LeagueController extends Controller{

    public function showUserData($derp){

        $vars = [];
        
        $why = $this->getMatchHistory($derp);
        

        $vars['name'] = $derp;
        $vars['parsed_stats'] = $why;

        $dump = 0;
        $dump = $this->getMatchHistory($derp, true);
        $vars['dump'] = $dump;

        // return View::make('why-you-suck', $vars);
        return Response::json($vars);

    }

    private function getUserID($user){
        $url = 'v1.4/summoner/by-name/' . rawurlencode($user) . '?api_key=f173513f-72a7-4a6d-a26d-bf202dc59821';
        $userInfo = $this->apiRequest($url);
        $userInfo = json_decode($userInfo);
        return $userInfo->$user->id;
    }

    private function getMatchHistory($user, $dump = false){

        $userID = $this->getUserID($user);

        $url = 'v2.2/matchhistory/'.$userID.'?api_key=f173513f-72a7-4a6d-a26d-bf202dc59821';
        $matches_data = json_decode($this->apiRequest($url));

        if (count($matches_data) < 1){
            return $this->throwError('no matches');
        }

        if ($dump === true){
            return $matches_data;
        }

        return $this->parseStats($matches_data);
        
    }

    private function parseStats($data){

        $wins = $total_creeps = $kda = $match_length = $total_match_length = $gpg = $total_kills = $total_deaths = $total_assists = $games = $levels = $total_wards = 0;


        foreach ($data->matches as $match) {

            if ($match->matchMode == 'CLASSIC'){
                $games++;

                $match_length = (($match->matchDuration)/60);

                $total_match_length = ($total_match_length + $match_length);

                if ($match->participants[0]->stats->winner === true){
                    $wins++;
                }

                $total_kills = $total_kills + $match->participants[0]->stats->kills;
                $total_deaths = $total_deaths + $match->participants[0]->stats->deaths;
                $total_assists = $total_assists + $match->participants[0]->stats->assists;

                $gpg = $gpg + $match->participants[0]->stats->goldEarned;

                $total_creeps = ($total_creeps + $match->participants[0]->stats->minionsKilled);

                $levels = $levels + $match->participants[0]->stats->champLevel;

                $total_wards = $total_wards + $match->participants[0]->stats->wardsPlaced;
            }

        }

        if ($games === 0){
           return $this->throwError('no games');
        }

        $kda_spread = round((($total_kills + $total_assists) / $total_deaths), 3);

        $average_level = round(($levels / $games), 0);

        $cpm = round(($total_creeps / $total_match_length), 3);

        $gpm = round(($gpg / $total_match_length), 3);

        $wpm = round(($total_wards / $games), 3);

        $stats = ['error' => 0,
                  'wins' => $wins, 
                  'kda' => $kda_spread, 
                  'cpm' => $cpm, 
                  'gpm' => $gpm, 
                  'games' => $games, 
                  'average_level' => $average_level, 
                  'total_wards' => $total_wards,
                  'wpm' => $wpm];

        return $stats;
    }

    private function apiRequest($url){
        $url = 'https://na.api.pvp.net/api/lol/na/'.$url;
        $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($ch);
                curl_close($ch);
                return $result;
    }

    private function throwError($error){
        $stats['error'] = 1;
        return $stats;
    }
    
}
