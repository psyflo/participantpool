<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\JsonException;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class LogController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get(Request $request)
    {
        try
        {
            /**
             * @var User
             */
            $user = auth()->user();

            /*
             * Check authorization
             */
            $authorized = $user->isAdmin();

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Get available log files
             */
            $files = glob(storage_path('logs/laravel-*.log'));
            $files = array_reverse($files);

            /*
             * Log levels to read
             * 
             * Available levels: emergency, alert, critical, error, warning, notice, info and debug
             * 
             * See https://datatracker.ietf.org/doc/html/rfc5424
             */
            $levels = ['error', 'warning'];

            /*
             * Read data from files up to records limit
             */
            $limit = setting('participantpool.log_entries_limit');
            $data = [];

            foreach ($files as $file)
            {
                $stop = false;

                /*
                 * Get content of current file
                 */
                $content = file_get_contents($file);

                /*
                 * Parse content
                 */
                $pattern = "/^\[(?<date>.*)\]\s(?<env>\w+)\.(?<level>\w+):(?<message>[^\\{\\[]*)/m";
                preg_match_all($pattern, $content, $matches, PREG_SET_ORDER, 0);

                /*
                 * Prepare data
                 */
                foreach ($matches as $match)
                {
                    /*
                     * Only add matches of requested log levels
                     */
                    if (in_array(strtolower($match['level']), $levels))
                    {
                        $data[] = ['date' => $match['date'], 'level' => strtolower($match['level']), 'message' => trim($match['message'])];
                    }

                    /*
                     * Check array size due to records limit
                     */
                    if(count($data) >= $limit)
                    {
                        $stop = true;
                        break;
                    }
                }

                /*
                 * Stop loop when records limit is reached
                 */
                if ($stop)
                {
                    break;
                }
            }

            /*
             * Sort data by date in descending order 
             */
            $sorted = collect($data)->sortByDesc('date')->values()->all();
            
            return response()->json($sorted);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to read log.'), 500);
        }
    }
}
