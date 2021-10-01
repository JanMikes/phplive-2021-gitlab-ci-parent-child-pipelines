<?php declare(strict_types=1);

$packages = ['A', 'B', 'C'];

$ciFileContent = '';

foreach ($packages as $package) {
    $ciFileContent .= <<<FILE_CONTENT
package-$package:
  trigger:
    include: "packages/$package/.gitlab-ci.yml"
    strategy: depend
  rules:
    - if: \$CI_MERGE_REQUEST_ID || \$CI_COMMIT_BRANCH == \$CI_DEFAULT_BRANCH
      changes:
        - packages/$package/**/*
FILE_CONTENT . "\n\n";
}

file_put_contents(__DIR__ . '/generated-gitlab-ci.yml', $ciFileContent);
