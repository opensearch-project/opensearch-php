- [Overview](#overview)
- [Branching](#branching)
  - [OpenSearch Branching](#opensearch-branching)
  - [Plugin Branching](#plugin-branching)
  - [Versioning](#versioning)
  - [Feature Branches](#feature-branches)
- [Release Labels](#release-labels)
- [Releasing](#releasing)
- [Backporting](#backporting)

## Overview

This document explains the release strategy for artifacts in this organization.

## Branching

Projects create a new branch when they need to start working on 2 separate versions of the product, with the `main` branch being the furthermost release. 

### OpenSearch Branching

[OpenSearch](https://github.com/opensearch-project/OpenSearch) typically tracks 3 releases in parallel. For example, given the last major release of 1.0, OpenSearch in this organization maintains the following active branches.

* **main**: The _next major_ release, currently 2.0. This is the branch where all merges take place, and code moves fast.
* **1.x**: The _next minor_ release, currently 1.1. Once a change is merged into `main`, decide whether to backport it to `1.x`.
* **1.0**: The _current_ release, currently 1.0. In between minor releases, only hotfixes (e.g. security) are backported to `1.0`. The next release out of this branch will be 1.0.1.

Label PRs with the next major version label (e.g. `2.0.0`) and merge changes into `main`. Label PRs that you believe need to be backported as `1.x` and `1.0`. Backport PRs by checking out the versioned branch, cherry-pick changes and open a PR against each target backport branch.

### Plugin Branching

Plugins, such as [job-scheduler](https://github.com/opensearch-project/job-scheduler) aren't as active as OpenSearch, and typically track 2 releases in parallel instead of 3. This still translates into 3 branches. For example, given the last major release of 1.0, job-scheduler maintains the following.

* **main**: The _next_ release, currently 1.1. This is the branch where all merges take place, and code moves fast.
* **1.x**: A common parent branch for the series of 1.x releases. This is where 1.x patches will be made when `main` becomes 2.0.
* **1.0**: The _current_ release, currently 1.0. This branch's parent is `1.x` to make future merges easier. 'In between minor releases, only hotfixes (e.g. security) are backported to `1.0`. The next release out of this branch will be 1.0.1.

### Versioning

Versions are incremented as soon as development starts on a given version to avoid confusion. In the examples above versions are as follows.

* OpenSearch: `main` = 2.0, `1.x` = 1.1, and `1.0` = 1.0
* job-scheduler: `main` = 1.1, `1.0` = 1.0

### Feature Branches

Do not creating branches in the upstream repo, use your fork, for the exception of long lasting feature branches that require active collaboration from multiple developers. Name feature branches `feature/<thing>`. Once the work is merged to `main`, please make sure to delete the feature branch.

## Release Labels

Repositories create consistent release labels, such as `v1.0.0`, `v1.1.0` and `v2.0.0`, as well as `patch` and `backport`. Use release labels to target an issue or a PR for a given release. See [MAINTAINERS](MAINTAINERS.md#triage-open-issues) for more information on triaging issues.

## Releasing

The release process is standard across repositories in this org and is run by a release manager volunteering from amongst [MAINTAINERS](MAINTAINERS.md).

## Backporting

This project follows [semantic versioning](https://semver.org/spec/v2.0.0.html). Backwards-incompatible changes always result in a new major version and will __never__ be backported. Small improvements and features will be backported to a new minor version (e.g. `1.1`). Security fixes will be backported to a new patch version (e.g. `1.0.1`).

To backport a change automatically, please refer to [backports](MAINTAINERS.md/#backports). To manually backport changes to release branches, here are the commands we typically run:

1. Checkout the target release branch and pull the latest changes from `upstream`. In the examples below, our target release branch is `1.x`.

```
git checkout 1.x
git pull upstream 1.x
```

2. Create a local branch for the backport. A convenient naming convention is _backport-\[PR-id\]-\[target-release-branch\]_.

```
git checkout -b backport-pr-xyz-1.x
```

3. Cherry-pick the commit to backport. Remember to include [DCO signoff](CONTRIBUTING.md#developer-certificate-of-origin).

```
git cherry-pick <commit-id> -s
```

4. Push the local branch to your fork.

```
git push origin backport-pr-xyz-1.x
```

5. Create a pull request for the change.