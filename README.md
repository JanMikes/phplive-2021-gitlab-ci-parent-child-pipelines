# Gitlab CI - intro into Parent-child + Dynamic child pipelines

Repository used for live demo on [PHPLive 2021 conference](https://phplive.cz).

Goal is to demonstrate through the demos, how to get from "classic approach" to parent-child pipelines, with dynamic child pipelines that based on changed code, will trigger pipelines only for those packages that are affected (and their dependencies).

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

Example of failed pipeline to demonstrate the UI and behaviour.

- [Code diff](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/merge_requests/3/diffs)
- [Pipeline](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/pipelines/380725401)

## Demo 4

Introduction of `depend` strategy to make parent pipeline fail if one of children fail. 

- [Code diff](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/merge_requests/4/diffs)
- [Pipeline](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/pipelines/380726572)

## Demo 5

Introduction to dynamic child pipelines with exactly the same behaviour - dynamically generating pipelines for all packages. This is 2 level parent-child pipelines.

- [Code diff](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/merge_requests/5/diffs)
- [Pipeline](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/pipelines/380736054)

## Demo 6

Introduction to merge request pipelines (without dynamic child pipelines yet) - trigger pipeline only package that has been directly changed.

- [Code diff](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/merge_requests/6/diffs)
- [Pipeline](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/pipelines/380746777)

## Demo 7

Merge request pipelines with dynamic child pipelines combined together.

- [Code diff](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/merge_requests/8/diffs)
- [Pipeline](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/pipelines/380763958)

## Demo 8

The "Ultimate demo" - merge request pipeline with dynamic child, run pipelines for package that has been directly changed + all dependent packages, to make sure nothing have been broken up.

- [Code diff](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/merge_requests/10/diffs)
- [Pipeline - package A changed (has no dependencies on it)](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/pipelines/380843155)
- [Pipeline - Package C changed (pkg A depends on C)](https://gitlab.com/janmikes/phplive-2021-parent-child-pipelines/-/pipelines/380844092)