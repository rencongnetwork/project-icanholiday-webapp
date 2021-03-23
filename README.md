<h1 align="center">Project : IcanHoliday Web Application</h1>

## Intro
IcanHoliday is a travel management web application built with PHP Codeigniter 3 and Bootsrtap 4.

## Features

Features included in this application include:

- Authentication
- Dashboard

## Project Board & Timeline

Project roadmap and timeline visit : https://app.asana.com/0/1200088975108893/overview

## Git Workflow

<p align="center"><img src="https://buddy.works/blog/images/gitflow.png"></p>

The bigger the project, the more control you need over what and when is released. Your projects require more unit and integration tests, which are now counted in hours. Usually, you don’t run such tests on branches where features are developed.

This can be resolved with Gitflow, invented and described by Vincent Driessen in 2010.

This workflow employs two parallel long-running branches:
- Master
- Development

“Master” is always ready to be released on LIVE, with everything fully tested and approved (production-ready).

“Develop” is the branch to which all feature branches are merged and where all tests are performed. Only when everything’s been thoroughly checked and fixed it can be merged to the Master.
