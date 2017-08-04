# HowTo: Hotfix deployment workflow

## Other docs you should know before

* [git flow commands](https://danielkummer.github.io/git-flow-cheatsheet/)
* [PROD Deployment](DEPLOYMENT.md/)

## Decide how to hotfix

It is important to decide together in developer and product owner team, how broken code could be hotfixed
without to many changes of current PROD code base. Only after this team discussion start preparing a hotfix.

If any other non hotfix relevant changes exists in one of our module or themes it is required to only add hotfix
relevant changes via patch in composer.json file for this module or theme.

## Prepare hotfix for deployment

* Get current master and develop branch of project to hotfix.

~~~
git checkout develop
git pull
git checkout master
git pull
~~~

* Find next hotfix number from current VERSION file in project root.
* Create hotfix branch with git flow command.

~~~
git flow hotfix start v2.x.x
~~~

* Check if their are any non-imported config YMLs in PROD environment.

    If you see any non-imported config files like block or other configs on PROD environment,
    download complete config set and copy paste changed configs into config/sync folder.
    
~~~
git add <LIST OF CONFIG YML FILES>
git commit -m "INREL-<ID> Re-import config YML files from PROD environment"
~~~

* Edit VERSION file with new hotfix number.

~~~
git add VERSION
git commit -m "INREL-<ID> Tagging hotfix v2.x.x of <PROJECTNAME> project"
~~~

* Finish hotfix with git flow command.

    At second git flow commit message enter *Tagging HOTFIX v2.x.x*

~~~
git flow hotfix finish v2.x.x
git push origin v2.x.x
git push --all
~~~

* Verify that the 3 upcoming travis deploys are successfully terminated.

## Hotfix deployment on PROD environment

* Login to both PROD web apps via ssh on acquia cloud. Grep credentials from Acquia Cloud backend.

* Create minimal PROD database backup on 1. web app of project you want to deploy.

~~~
~/bs-common-acquia-utils/bin/backup-prod-db-minimal
~~~

* Switch to hotfix tag in Acquia cloud backend for project you want to release.

* Verify in Acquia cloud backend that hotfix deployment is successfully terminated.

* Monitor load of both PROD web apps with top via ssh.

~~~
htop
~~~

* Clear varnish cache via Acquia cloud backend when load on both PROD web apps is lower then 3.