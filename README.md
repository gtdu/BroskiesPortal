# Broskies Portal

This project serves as the orchestration platform for submodules. This project was originally developed as a way to coordinate a large set of functionality that is into a single, coherent platform that is easy to adapt.

## Original Use Case
As a fraternity, we had a number of ideas for small applications that would make life easier for our members (ex: a central repository of links/resources, everybody's contact information, funny stories, etc.). However, all of these ideas were too small to make into their own standalone application that would gain traction; hence `Broskies Portal` was born.

This central platform serves as the login and permission management center that all of these small "micro applications" can tap into.

__PLEASE NOTE:__ This was designed to authenticate users using Slack. We generally recommend that the Slack administrator also administer this application. Once you've added a user to the Slack workspace, also add them to this application using their Slack User ID.

## Adding New Modules
To add a new module, you must first navigate to the `Manage Modules` tab with a `Administrator` account. From here, you will generate an API key for that module. Now, all a sub-application needs to do is make an API call to `https://YOUR_URL.com/api/` with a POST request for `api_key` (which you just generated) and `session_token`.

A `standard` module will be loaded as an iFrame within the main application window. You will enter the URL that should be displayed when you generate the api key and when the application is loaded, there will be a GET parameter that contains the current user's session token.

You can also create an `external` module that will just open your URL + the session token in a new tab.

### Example Module
| Field | Value|
|---|---|
| Module Name | Resources  |
| API KEY | 123456789 |
| Root URL | example.com/app |

When a user attempts to use this app, the iFrame will load `https://example.com/app?session_token=ABCDEFG`

To authenticate the user, call 'https://broskies.example.com/api/' with `{api_key=123456789, session_token=ABCDEFG}`

## Standard Permission Levels
Permissions are handled as an integer. The core application will treat `0` as the user should not have access. `1` and above will be treated as they have access but it is your responsibility to decide what each level means. You should also specify a human-readable name for each level when creating the module

### Example Permission Scale
  0. No Access
  1. Read Only Access
  2. Write Access
  3. Admin Access

## Core Permissions
If a user is designated as a `Administrator`, then they will also have access to the `Manage Users` and `Manage Modules` tab. This allows them to generate API keys, create new accounts, modify permissions of accounts, etc.

## Setup
    1. Rename `example config.ini` to `config.ini`
    2. Update the variables in `config.ini`
    3. Run `setup.sql` to create the database and default data

## To-Do
  - Allow modules to adjust permissions themselves
  
  Webhook test 5
