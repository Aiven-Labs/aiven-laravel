<?php

namespace LornaJane\AivenLaravel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AivenGet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aiven:getconfig
        {--project= : Name of the Aiven project to use (overrides any configured default)}
        {--service= : Service to get the config of}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get config of an Aiven service to use in .env or other environment setup';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // check there's a token
        $token = config("aiven.api_token");
        if(!$token) {
            $this->error('Set an Aiven API token as AIVEN_API_TOKEN in the environment');
            return 1;
        }

        // make sure we have a project
        $project = config("aiven.project");
        if($localproject = $this->option('project')) {
            $project = $localproject;
        }

        if(!$project) {
            $this->error('Set a project with --project or configure AIVEN_DEFAULT_PROJECT in the environment');
            return 1;
        }

        // make sure we have a service
        if($service = $this->option("service")) {
            // err, cool?
        } else {
            $this->error('Use --service to specify which database to target');
            return 1;
        }

        // make the API call
        $response = Http::withToken($token)->get(
            "https://api.aiven.io/v1/project/" . $project .
            "/service/" . $service);
        if($response->status() == 200) {
            $data = json_decode($response->body(), true);

            // ditch any trailing query elements
            $url_bits = explode("?", $data["service"]["service_uri"]);
            $url = $url_bits[0];

            $type = $data["service"]["service_type"];
            switch($type) {
                case "mysql":
                    $this->line("DATABASE_URL=$url");
                    break;

                case "pg":
                    $this->line("DATABASE_URL=$url");
                    $this->line("DB_CONNECTION=pgsql");
                    break;

                case "redis":
                    $this->line("REDIS_URL=$url");
                    break;

                case "opensearch":
                    $this->line($url);
                    break;

                default:
                    $this->line("DATABASE_URL=$url");
                    break;
            }
            return 0;
        }

        // no success response
        return 1;
    }
}
