<?php

namespace App\Http\Controllers\Aiku;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateLatestDeploymentRequest;
use App\Models\Aiku\Deployment;

use Illuminate\Http\JsonResponse;
use PHLAK\SemVer\Version;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;

class DeploymentController extends Controller
{

    public function updateLatest(UpdateLatestDeploymentRequest $request)
    {
        if ($deployment = Deployment::latest()->first()) {
            $deployment->update($request->all());
            $changes= $deployment->getChanges();
            return response()->json(
                [
                    'updated'=>count($changes),
                    'fields'=>$changes
                ],

            );

            //return $deployment;
        } else {
            return response()->json(
                [
                    'message' => 'There is no deployments.'
                ],
                404
            );
        }
    }

    public function latest(): JsonResponse|Deployment
    {
        if ($deployment = Deployment::latest()->first()) {
            return $deployment;
        } else {
            return response()->json(
                [
                    'message' => 'There is no deployments.'
                ],
                404
            );
        }
    }

    public function show($deploymentID): JsonResponse|Deployment
    {
        if ($deployment = Deployment::find($deploymentID)) {
            return $deployment;
        } else {
            return response()->json(
                [
                    'message' => 'Record not found.'
                ],
                404
            );
        }
    }

    /**
     * @throws \PHLAK\SemVer\Exceptions\InvalidVersionException
     */
    public function store(): Deployment|JsonResponse
    {
        $data = [
            'skip' => []
        ];


        $currentHash = $this->runGitCommand('git describe --always');
        if ($latestDeployment = Deployment::latest()->first()) {
            $latestHash = $latestDeployment->hash;


            if (!$this->validateHash($currentHash) or !$this->validateHash($latestHash)) {
                return response()->json([
                                            'msg' => "Invalid hash $currentHash or $latestHash",

                                        ], 400);
            } else {
                $filesChanged = $this->runGitCommand("git diff --name-only $currentHash $latestHash");

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

        try {
            return Deployment::create([
                                          'version' => (string)$version,
                                          'hash'    => $currentHash,
                                          'data'    => $data
                                      ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json(
                [
                    'message' => 'Error creating the deployment.'
                ],
                404
            );
        }
    }

    private function runGitCommand($command): string
    {
        $path = config('deployments.repo_path');


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
