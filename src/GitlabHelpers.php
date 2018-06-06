<?php

namespace VIITech\Helpers;

use Exception;
use Gitlab\Client;
use Gitlab\Model\Issue;
use Gitlab\Model\Project;

class GitlabHelpers
{

    /**
     * List all projects from Gitlab
     * @param string $api_url API URL (example: http://git.example.com/api/v3/)
     * @param string $auth_token Authentication token
     * @return array|Exception
     */
    public static function listGitlabProjects($api_url, $auth_token)
    {
        try {
            # Init Gitlab Client
            $client = self::initClient($api_url, $auth_token);
            # List all projects
            return $client->api('projects')->all();
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Initialize Gitlab Client
     * @param string $api_url API URL (example: http://git.example.com/api/v4/)
     * @param string $auth_token Authentication token
     * @return Client|null
     */
    public static function initClient($api_url, $auth_token)
    {
        return Client::create($api_url)->authenticate($auth_token, Client::AUTH_URL_TOKEN);
    }

    /**
     * List Gitlab issues in a Project
     * @param string $api_url API URL (example: http://git.example.com/api/v3/)
     * @param string $auth_token Authentication token
     * @param string $project_id Project ID
     * @param int $page_number Page Number (Default is 1)
     * @param int $issues_per_page Issues Page Page (Default is 10)
     * @return array|Exception|null
     */
    public static function listGitlabIssuesInProject($api_url, $auth_token, $project_id, $page_number = 1, $issues_per_page = 10)
    {
        try {
            # Init Gitlab Client
            $client = self::initClient($api_url, $auth_token);
            # List all Gitlab issues
            return $client->api('issues')->all($project_id, [
                "page" => $page_number,
                "per_page" => $issues_per_page
            ]);
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Create Gitlab issue
     * @param string $api_url API URL (example: http://git.example.com/api/v3/)
     * @param string $auth_token Authentication token
     * @param string $project_id Project ID
     * @param string $title Issue title
     * @param string $description Issue description
     * @param string $labels Issue labels
     * @return Issue|Exception
     */
    public static function createGitlabIssue($api_url, $auth_token, $project_id, $title, $description, $labels = "")
    {
        try {
            # Init Gitlab Client
            $client = self::initClient($api_url, $auth_token);
            # Creating a new issue
            $project = new Project($project_id, $client);
            return $project->createIssue($title, [
                'description' => $description,
                'issues_enabled' => true,
                'labels' => $labels
            ]);
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Close Gitlab issue
     * @param string $api_url API URL (example: http://git.example.com/api/v3/)
     * @param string $auth_token Authentication token
     * @param string $project_id Project ID
     * @param string $issue_id Issue ID (not IID)
     * @return Issue|Exception
     */
    public static function closeGitlabIssue($api_url, $auth_token, $project_id, $issue_id)
    {
        try {
            # Init Gitlab Client
            $client = self::initClient($api_url, $auth_token);
            # Init Project variable
            $project = new Project($project_id, $client);
            # Close an existing issue
            return $project->closeIssue($issue_id);
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Delete Gitlab issue
     * @param string $api_url API URL (example: http://git.example.com/api/v3/)
     * @param string $auth_token Authentication token
     * @param string $project_id Project ID
     * @param string $issue_id Issue ID (not IID)
     * @return Issue|Exception
     */
    public static function deleteGitlabIssue($api_url, $auth_token, $project_id, $issue_id)
    {
        try {
            # Init Gitlab Client
            $client = self::initClient($api_url, $auth_token);
            # Delete an existing issue
            return $client->api('issues')->remove($project_id, $issue_id);
        } catch (Exception $e) {
            return $e;
        }
    }
}