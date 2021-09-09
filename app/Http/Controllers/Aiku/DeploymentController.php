<?php

namespace App\Http\Controllers\Aiku;

use App\Http\Controllers\Controller;
use App\Models\Aiku\Deployment;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use PHLAK\SemVer\Version;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;

class DeploymentController extends Controller
{
    public function latest(): Deployment
    {
        return Deployment::latest()->first();

    }

    public function show($deploymentID): Deployment
    {
        return Deployment::findOrFail($deploymentID);

    }

    /**
     * @throws \PHLAK\SemVer\Exceptions\InvalidVersionException
     */
    public function store(): Response|JsonResponse|Application|ResponseFactory
    {
        $data = [
            'skip' => []
        ];


        $currentHash = $this->runShellCommand('git describe --always');
        if ($latestDeployment = Deployment::latest()->first()) {
            $latestHash = $latestDeployment->hash;


            if (!$this->validateHash($currentHash) or !$this->validateHash($latestHash)) {
                return response()->json([
                                            'msg' => "Invalid hash $currentHash or $latestHash",

                                        ], 400);
            } else {
                $filesChanged = $this->runShellCommand("git diff --name-only $currentHash $latestHash");

                if (!preg_match('/composer\.lock/', $filesChanged)) {
                    $data['skip']['composer_install'] = true;
                }
                if (!preg_match('/package\.lock/', $filesChanged)) {
                    $data['skip']['npm_install'] = true;
                }

                if (!preg_match('/resources/', $filesChanged)) {
                    $data['skip']['build'] = true;
                }
            }

            $version = Version::parse($latestDeployment->version);

            if ($currentHash == $latestHash) {
                $build = (int)$version->build ?? 0;
                $build++;

                $version->setBuild(sprintf('%03d', $build));
            } else {
                $version->incrementPatch();
            }
        } else {
            $version = new Version();
        }


        $deployment = Deployment::create([
                                             'version' => (string)$version,
                                             'hash'    => $currentHash,
                                             'data'    => $data
                                         ]);


        return response($deployment, 200);
    }

    private function runShellCommand($command): string
    {
        $path = base_path();

        try {
            if (method_exists(Process::class, 'fromShellCommandline')) {
                $process = Process::fromShellCommandline($command, $path);
            } else {
                $process = new Process([$command], $path);
            }

            $process->mustRun();

            return trim($process->getOutput());
        } catch (RuntimeException) {
            return '';
        }
    }

    private function validateHash($hash): bool
    {
        return preg_match('/^[0-9a-f]{7,40}$/', $hash) == true;
    }
}
