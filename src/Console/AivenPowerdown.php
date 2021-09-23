<?php

namespace LornaJane\AivenLaravel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AivenPowerdown extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aiven:powerdown
        {--project= : Name of the Aiven project to use (overrides any configured default)}
        {--service= : Service to power off}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Power off a running Aiven service';

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
        $response = Http::withToken($token)->put(
            "https://api.aiven.io/v1/project/" . $project .
            "/service/" . $service,
            ["powered" => false]
        );
        if($response->status() == 200) {
            $data = json_decode($response->body(), true);
            $this->info($service . " service state: " . $data["service"]["state"]);
            return 0;
        }

        // no success response
        return 1;
    }
}
