<?php

declare(strict_types=1);

use Nette\Utils\Finder;
use Nette\Utils\Json;
use Nette\Utils\Strings;

require_once __DIR__ . '/../vendor/autoload.php';

$template = <<<TEMPLATE
{{JOB_NAME}}:
  trigger:
    include: {{GITLAB_CI_FILE}}
    strategy: depend
  rules:
    - if: \$CI_MERGE_REQUEST_ID || \$CI_COMMIT_BRANCH == \$CI_DEFAULT_BRANCH
      changes:
{{DEPENDENT_FILES}}
TEMPLATE . "\n\n";

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

    // Self-dependency
    $dependentOnPackages[(string) $file][] = Strings::after(realpath($file->getPath()), realpath(__DIR__ . '/../') . '/');

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

foreach ($dependentOnPackages as $gitlabCiFile => $dependencies) {
    $relativePathToGitlabCiFile = Strings::after(realpath($gitlabCiFile), realpath(getcwd()) . '/');
    $jobName = str_replace('/', '-', Strings::before($relativePathToGitlabCiFile, '/.gitlab-ci.yml'));
    $dependentOnPaths = '';
    $ciTriggerFile = $relativePathToGitlabCiFile;

    $packageDirectoryRoot = str_replace('/.gitlab-ci.yml', '', $relativePathToGitlabCiFile);

    foreach ($dependencies as $dependency) {
        $dependentOnPaths .= "              - $dependency/**/*\n";
    }

    $monorepoCiFile = str_replace('.gitlab-ci.yml', '.gitlab-ci.monorepo.yml', $relativePathToGitlabCiFile);
    if (is_file($monorepoCiFile)) {
        $ciTriggerFile = $monorepoCiFile;
    }

    $finalFileContents .= "\n" . str_replace(
            ['{{JOB_NAME}}', '{{GITLAB_CI_FILE}}', '{{DEPENDENT_FILES}}'],
            [$jobName, $ciTriggerFile, $dependentOnPaths],
            $template
        );
}

file_put_contents(__DIR__ . '/generated-gitlab-ci.yml', $finalFileContents);
