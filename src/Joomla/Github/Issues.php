<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github;

use Joomla\Uri\Uri;
use Joomla\Date\Date;

/**
 * GitHub API Issues class for the Joomla Framework.
 *
 * @since  1.0
 */
class Issues extends Object
{
	/**
	 * Method to create an issue.
	 *
	 * @param   string   $user       The name of the owner of the GitHub repository.
	 * @param   string   $repo       The name of the GitHub repository.
	 * @param   string   $title      The title of the new issue.
	 * @param   string   $body       The body text for the new issue.
	 * @param   string   $assignee   The login for the GitHub user that this issue should be assigned to.
	 * @param   integer  $milestone  The milestone to associate this issue with.
	 * @param   array    $labels     The labels to associate with this issue.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function create($user, $repo, $title, $body = null, $assignee = null, $milestone = null, array $labels = null)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/issues';

		// Ensure that we have a non-associative array.
		if (isset($labels))
		{
			$labels = array_values($labels);
		}

		// Build the request data.
		$data = json_encode(
			array(
				'title' => $title,
				'assignee' => $assignee,
				'milestone' => $milestone,
				'labels' => $labels,
				'body' => $body
			)
		);

		// Send the request.
		return $this->processResponse($this->client->post($this->fetchUrl($path), $data), 201);
	}

	/**
	 * Method to create a comment on an issue.
	 *
	 * @param   string   $user     The name of the owner of the GitHub repository.
	 * @param   string   $repo     The name of the GitHub repository.
	 * @param   integer  $issueId  The issue number.
	 * @param   string   $body     The comment body text.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function createComment($user, $repo, $issueId, $body)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/issues/' . (int) $issueId . '/comments';

		// Build the request data.
		$data = json_encode(
			array(
				'body' => $body,
			)
		);

		// Send the request.
		return $this->processResponse($this->client->post($this->fetchUrl($path), $data), 201);
	}

	/**
	 * Method to create a label on a repo.
	 *
	 * @param   string  $user   The name of the owner of the GitHub repository.
	 * @param   string  $repo   The name of the GitHub repository.
	 * @param   string  $name   The label name.
	 * @param   string  $color  The label color.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function createLabel($user, $repo, $name, $color)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/labels';

		// Build the request data.
		$data = json_encode(
			array(
				'name' => $name,
				'color' => $color
			)
		);

		// Send the request.
		return $this->processResponse($this->client->post($this->fetchUrl($path), $data), 201);
	}

	/**
	 * Method to delete a comment on an issue.
	 *
	 * @param   string   $user       The name of the owner of the GitHub repository.
	 * @param   string   $repo       The name of the GitHub repository.
	 * @param   integer  $commentId  The id of the comment to delete.
	 *
	 * @return  mixed
	 *
	 * @since   1.0
	 */
	public function deleteComment($user, $repo, $commentId)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/issues/comments/' . (int) $commentId;

		// Send the request.
		return $this->processResponse($this->client->delete($this->fetchUrl($path)), 204);
	}

	/**
	 * Method to delete a label on a repo.
	 *
	 * @param   string  $user   The name of the owner of the GitHub repository.
	 * @param   string  $repo   The name of the GitHub repository.
	 * @param   string  $label  The label name.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function deleteLabel($user, $repo, $label)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/labels/' . $label;

		// Send the request.
		return $this->processResponse($this->client->delete($this->fetchUrl($path)), 204);
	}

	/**
	 * Method to update an issue.
	 *
	 * @param   string   $user       The name of the owner of the GitHub repository.
	 * @param   string   $repo       The name of the GitHub repository.
	 * @param   integer  $issueId    The issue number.
	 * @param   string   $state      The optional new state for the issue. [open, closed]
	 * @param   string   $title      The title of the new issue.
	 * @param   string   $body       The body text for the new issue.
	 * @param   string   $assignee   The login for the GitHub user that this issue should be assigned to.
	 * @param   integer  $milestone  The milestone to associate this issue with.
	 * @param   array    $labels     The labels to associate with this issue.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function edit($user, $repo, $issueId, $state = null, $title = null, $body = null, $assignee = null, $milestone = null, array $labels = null)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/issues/' . (int) $issueId;

		// Create the data object.
		$data = new \stdClass;

		// If a title is set add it to the data object.
		if (isset($title))
		{
			$data->title = $title;
		}

		// If a body is set add it to the data object.
		if (isset($body))
		{
			$data->body = $body;
		}

		// If a state is set add it to the data object.
		if (isset($state))
		{
			$data->state = $state;
		}

		// If an assignee is set add it to the data object.
		if (isset($assignee))
		{
			$data->assignee = $assignee;
		}

		// If a milestone is set add it to the data object.
		if (isset($milestone))
		{
			$data->milestone = $milestone;
		}

		// If labels are set add them to the data object.
		if (isset($labels))
		{
			// Ensure that we have a non-associative array.
			if (isset($labels))
			{
				$labels = array_values($labels);
			}

			$data->labels = $labels;
		}

		// Encode the request data.
		$data = json_encode($data);

		// Send the request.
		return $this->processResponse($this->client->patch($this->fetchUrl($path), $data), 200);
	}

	/**
	 * Method to update a comment on an issue.
	 *
	 * @param   string   $user       The name of the owner of the GitHub repository.
	 * @param   string   $repo       The name of the GitHub repository.
	 * @param   integer  $commentId  The id of the comment to update.
	 * @param   string   $body       The new body text for the comment.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function editComment($user, $repo, $commentId, $body)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/issues/comments/' . (int) $commentId;

		// Build the request data.
		$data = json_encode(
			array(
				'body' => $body
			)
		);

		// Send the request.
		return $this->processResponse($this->client->patch($this->fetchUrl($path), $data), 200);
	}

	/**
	 * Method to update a label on a repo.
	 *
	 * @param   string  $user   The name of the owner of the GitHub repository.
	 * @param   string  $repo   The name of the GitHub repository.
	 * @param   string  $label  The label name.
	 * @param   string  $name   The label name.
	 * @param   string  $color  The label color.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function editLabel($user, $repo, $label, $name, $color)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/labels/' . $label;

		// Build the request data.
		$data = json_encode(
			array(
				'name' => $name,
				'color' => $color
			)
		);

		// Send the request.
		return $this->processResponse($this->client->patch($this->fetchUrl($path), $data), 200);
	}

	/**
	 * Method to get a single issue.
	 *
	 * @param   string   $user     The name of the owner of the GitHub repository.
	 * @param   string   $repo     The name of the GitHub repository.
	 * @param   integer  $issueId  The issue number.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function get($user, $repo, $issueId)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/issues/' . (int) $issueId;

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path)), 200);
	}

	/**
	 * Method to get a specific comment on an issue.
	 *
	 * @param   string   $user       The name of the owner of the GitHub repository.
	 * @param   string   $repo       The name of the GitHub repository.
	 * @param   integer  $commentId  The comment id to get.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function getComment($user, $repo, $commentId)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/issues/comments/' . (int) $commentId;

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path)), 200);
	}

	/**
	 * Method to get the list of comments on an issue.
	 *
	 * @param   string   $user     The name of the owner of the GitHub repository.
	 * @param   string   $repo     The name of the GitHub repository.
	 * @param   integer  $issueId  The issue number.
	 * @param   integer  $page     The page number from which to get items.
	 * @param   integer  $limit    The number of items on a page.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getComments($user, $repo, $issueId, $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/issues/' . (int) $issueId . '/comments';

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path, $page, $limit)), 200);
	}

	/**
	 * Method to get a specific label on a repo.
	 *
	 * @param   string  $user  The name of the owner of the GitHub repository.
	 * @param   string  $repo  The name of the GitHub repository.
	 * @param   string  $name  The label name to get.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function getLabel($user, $repo, $name)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/labels/' . $name;

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path)), 200);
	}

	/**
	 * Method to get the list of labels on a repo.
	 *
	 * @param   string  $user  The name of the owner of the GitHub repository.
	 * @param   string  $repo  The name of the GitHub repository.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getLabels($user, $repo)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/labels';

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path)), 200);
	}

	/**
	 * Method to list an authenticated user's issues.
	 *
	 * @param   string   $filter     The filter type: assigned, created, mentioned, subscribed.
	 * @param   string   $state      The optional state to filter requests by. [open, closed]
	 * @param   string   $labels     The list of comma separated Label names. Example: bug,ui,@high.
	 * @param   string   $sort       The sort order: created, updated, comments, default: created.
	 * @param   string   $direction  The list direction: asc or desc, default: desc.
	 * @param   Date     $since      The date/time since when issues should be returned.
	 * @param   integer  $page       The page number from which to get items.
	 * @param   integer  $limit      The number of items on a page.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getList($filter = null, $state = null, $labels = null, $sort = null, $direction = null, Date $since = null, $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/issues';

		// TODO Implement the filtering options.

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path, $page, $limit)), 200);
	}

	/**
	 * Method to list issues.
	 *
	 * @param   string   $user       The name of the owner of the GitHub repository.
	 * @param   string   $repo       The name of the GitHub repository.
	 * @param   string   $milestone  The milestone number, 'none', or *.
	 * @param   string   $state      The optional state to filter requests by. [open, closed]
	 * @param   string   $assignee   The assignee name, 'none', or *.
	 * @param   string   $mentioned  The GitHub user name.
	 * @param   string   $labels     The list of comma separated Label names. Example: bug,ui,@high.
	 * @param   string   $sort       The sort order: created, updated, comments, default: created.
	 * @param   string   $direction  The list direction: asc or desc, default: desc.
	 * @param   Date     $since      The date/time since when issues should be returned.
	 * @param   integer  $page       The page number from which to get items.
	 * @param   integer  $limit      The number of items on a page.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getListByRepository($user, $repo, $milestone = null, $state = null, $assignee = null, $mentioned = null, $labels = null,
		$sort = null, $direction = null, Date $since = null, $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/issues';

		$uri = new Uri($this->fetchUrl($path, $page, $limit));

		if ($milestone)
		{
			$uri->setVar('milestone', $milestone);
		}

		if ($state)
		{
			$uri->setVar('state', $state);
		}

		if ($assignee)
		{
			$uri->setVar('assignee', $assignee);
		}

		if ($mentioned)
		{
			$uri->setVar('mentioned', $mentioned);
		}

		if ($labels)
		{
			$uri->setVar('labels', $labels);
		}

		if ($sort)
		{
			$uri->setVar('sort', $sort);
		}

		if ($direction)
		{
			$uri->setVar('direction', $direction);
		}

		if ($since)
		{
			$uri->setVar('since', $since->format(\DateTime::RFC3339));
		}

		// Send the request.
		return $this->processResponse($this->client->get((string) $uri), 200);
	}
}
