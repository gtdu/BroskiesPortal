# Broskies Portal

This project serves as the orchestration platform for submodules. This project was originally developed as a way to coordinate a large set of functionality that is into a single, coherent platform that is easy to adapt.

## Original Use Case
As a fraternity, we had a number of ideas for small applications that would make life easier for our members (ex: a central repository of links/resources, everybody's contact information, funny stories, etc.). However, all of these ideas were too small to make into their own standalone application that would gain traction; hence `Broskies Portal` was born.

This central platform serves as the login and permission management center all of these small "micro applications" can tap into.

## Authentication
Our fraternity has a Slack workspace that we all use for communication, and we built this application around that. Thus, we use Slack to handle authentication. We recommend that the workspace administrator also serve as the administrator of this application. Note that all of this will work with the free tier of Slack.

### Adding New Users
First, you must add the user to the Slack workspace and obtain their Slack User ID. This is the value that you use the in Create New User form on this application. Once this step has been completed, then they can log in no problem.

### Setting Up The Integration
  1. Create a new Slack App and add it to the workspace. (Done here)[https://api.slack.com/apps]
  2. You don't need to submit it as this app will only ever be used with your workspace
  3. Obtain the Slack Client ID, Client Secret and OAuth Access Token. Add all of these to your `config.ini`
  4. Configure your Redirect URLs to `https://YOUR.SERVER.COM/slackCallback.php`
  5. Configure your Scopes to include `chat:write` for `Bot Scopes` and `identity:basic, identity:email` for `User Scopes`
After you've completed all of this, you should be able to successfully authenticate with Slack.
_(Optional but recommended) You will want to update the Channel and User IDs within `includes/homePage.php` in the chaos section to be more fitting for your culture_

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
Permissions are handled as an integer. The core application will treat `0` as the user should not have access. `1` and above will be treated as they have access, but it is your responsibility to decide what each level means. You should also specify a human-readable name for each level when creating the module.

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
