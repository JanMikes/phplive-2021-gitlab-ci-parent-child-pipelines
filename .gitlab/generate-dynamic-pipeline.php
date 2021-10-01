<?php

declare(strict_types=1);

use Nette\Utils\Finder;
use Nette\Utils\Json;
use Nette\Utils\Strings;

require_once __DIR__ . '/../vendor/autoload.php';

$inputTemplateFile = __DIR__ . '/.gitlab-dynamic-children.tpl.yml';
$outputTemplateFile = __DIR__ . '/.gitlab-dynamic-children.yml';

$input = __DIR__ . '/../packages';
if (Strings::startsWith($input, '/') === false) {
    $input = getcwd() . '/' . $input;
}

$composerJson = Json::decode(file_get_contents(__DIR__ . '/../composer.json'));
$allPsr4Roots = $composerJson->autoload->{"psr-4"};
$dependentOnPackages = [];

foreach (Finder::findFiles('.gitlab-ci.yml')->from($input) as $file) {
    if (realpath($file->getPath()) === realpath($input)) {
        continue;
    }

    $phpFiles = Finder::findFiles('*.php')->from($file->getPath()); // finder;
    $psr4Roots = clone $allPsr4Roots;

    foreach ($phpFiles as $phpFile) {
        $phpFileContent = file_get_contents((string) $phpFile);

        foreach ($psr4Roots as $psr4Namespace => $path) {
            if (Strings::contains($phpFileContent, ' ' . $psr4Namespace)) {
                $dependentOnPackages[(string) $file][] = $path;
                unset($psr4Roots->$psr4Namespace);
            }
        }
    }
}

$finalFileContents = '';
$template = file_get_contents($inputTemplateFile);

foreach ($dependentOnPackages as $gitlabCiFile => $dependencies) {
    $relativePathToGitlabCiFile = Strings::after(realpath($gitlabCiFile), realpath(getcwd()) . '/');
    $jobName = str_replace('/', '-', Strings::before($relativePathToGitlabCiFile, '/.gitlab-ci.yml'));
    $dependentOnPaths = '';
    $ciTriggerFile = $relativePathToGitlabCiFile;

    $packageDirectoryRoot = str_replace('/.gitlab-ci.yml', '', $relativePathToGitlabCiFile);
    $dependentOnPaths .= "              - $packageDirectoryRoot/**/*\n";


    foreach ($dependencies as $dependency) {
        $dependentOnPaths .= "              - $dependency/**/*\n";
    }

    $finalFileContents .= "\n" . str_replace(
            ['{{JOB_NAME}}', '{{GITLAB_CI_FILE}}', '{{DEPENDENT_FILES}}'],
            [$jobName, $ciTriggerFile, $dependentOnPaths],
            $template
        );
}

file_put_contents($outputTemplateFile, $finalFileContents);
