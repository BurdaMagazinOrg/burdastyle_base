# HowTo: Release deployment workflow

## Other docs you should know before

* [git flow commands](https://danielkummer.github.io/git-flow-cheatsheet/)

## Prepare release for deployment

* Get current master and develop branch of project to release.

~~~
git checkout master
git pull
git checkout develop
git pull
~~~

* Find next release number from current VERSION file in project root.
* Create release branch with git flow command.

~~~
git flow release start v2.x.x
~~~

* Check if their are any non-imported config YMLs in PROD environment.

    If you see any non-imported config files like block or other configs on PROD environment,
    download complete config set and copy paste changed configs into config/sync folder.
    
~~~
git add <LIST OF CONFIG YML FILES>
git commit -m "INREL-<ID> Re-import config YML files from PROD environment"
~~~
    
* Edit VERSION file with new release number.

~~~
git add VERSION
git commit -m "INREL-<ID> Tagging relase v2.x.x of <PROJECTNAME> project"
~~~

* Finish release with git flow command.

    At second git flow commit message enter *Tagging RELEASE v2.x.x*

~~~
git flow release finish v2.x.x
git push origin v2.x.x
git push --all
~~~

* Verify that the 3 upcoming travis deploys are successfully terminated.

## Release deployment on PROD environment

* Login to both PROD web apps via ssh on acquia cloud. Grep credentials from Acquia Cloud backend.

* Create minimal PROD database backup on 1. web app of project you want to deploy.

~~~
~/bs-common-acquia-utils/bin/backup-prod-db-minimal
~~~

* Switch to release tag in Acquia cloud backend for project you want to release.

* Verify in Acquia cloud backend that release deployment is successfully terminated.

* Monitor load of both PROD web apps with top via ssh.

~~~
htop
~~~

* Clear varnish cache via Acquia cloud backend when load on both PROD web apps is lower then 3.