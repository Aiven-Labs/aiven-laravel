<?php

namespace LornaJane\AivenLaravel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AivenList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aiven:list
        {--project= : Name of the Aiven project to use (overrides any configured default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List available Aiven services';

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

        // make the API call
        $response = Http::withToken($token)->get("https://api.aiven.io/v1/project/" . $project . "/service");
        $data = json_decode($response->body(), true);
        if(isset($data["services"])) {
            $services = [];
            foreach($data["services"] as $service) {
                array_push($services, [$service["service_name"], $service["service_type"]]);
            }
            $this->table(["Service Name", "Service Type"], $services);

        } elseif(isset($data["errors"])) {
            foreach($data["errors"] as $error) {
                $this->line($error["message"]);
                $this->error("Aiven returned an error, check above for response output");
            }
        } else {
            $this->info("No data");
        }
        return 0;
    }
}
