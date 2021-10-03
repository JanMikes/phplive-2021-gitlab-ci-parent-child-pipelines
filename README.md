# Gitlab CI - intro into Parent-child + Dynamic child pipelines

Repository used for live demo on [PHPLive 2021 conference](https://phplive.cz).

Goal is to demonstrate through the demos, how to get from "classic approach" to parent-child pipelines, with dynamic child pipelines that will trigger based on changed code only for those packages that are affected (and their dependencies).

## Useful resources

- [Gitlab docs - Parent-child pipelines](https://docs.gitlab.com/ee/ci/pipelines/parent_child_pipelines.html)
- [Gitlab docs - Merge request pipelines](https://docs.gitlab.com/ee/ci/pipelines/merge_request_pipelines.html)

## Demo 1

"Classic approach" - monorepo without parent-child pipelines, everything run in the same pipeline. 

- [Code](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/blob/demo-1-standard-pipelines/.gitlab-ci.yml)
- [Pipeline](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/pipelines/380721770)

## Demo 2

Simple implementation of parent-child pipelines.

- [Code diff](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/merge_requests/2/diffs)
- [Pipeline](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/pipelines/380919146)

## Demo 3

## Demo 4

## Demo 5

## Demo 6

## Demo 7

## Demo 8