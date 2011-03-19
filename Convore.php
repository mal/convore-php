<?php
	
	/**
	*
	* @package Convore-PHP
	* @version $Id$
	* @copyright (c) 2011 Chronium Labs LLC
	* @license http://opensource.org/licenses/mit-license.php MIT License
	*
	*/
	
	class Convore {
		
		private $credentials;
		private $base_url;
		
		/**
		 * Constructor method for Convore
		 * @param String $user Username
		 * @param String $pass Password
		 **/
		public function __construct($user, $pass) {
			$this->credentials = vsprintf('%s:%s', array($user, $pass));
			$this->base_url = "https://convore.com/api";
		}
		
		/**
		 * Verify your account credentials
		 * @return Object json
		 **/
		function verifyAccount() {
			return $this->methodCall('/account/verify.json', 'get');
		}
		
		/**
		 * Mark all unread topic messages as read
		 * @return Object json
		 **/
		
		function markAllRead() {
			return $this->methodCall('/account/mark_read.json', 'get');
		}
		
		/**
		 * Get a list of all users currently online
		 * @return Object json
		 **/
		function getUsersCurrentlyOnline() {
			return $this->methodCall('/account/online.json', 'get');
		}
		
		// Groups methods
		
		/**
		 * Get all groups user is currently a part of
		 * @return Object json
		 **/
		function getAllGroups() {
			return $this->methodCall('/groups.json', 'get');
		}
		
		/**
		 * Create a group for authenticated user
		 * @param String $name Name of group
		 * @param String $kind either public or private
		 * @param String $desc Optional description for group (defaults to null)
		 * @param String $slug Optional custom slug for created group (defaults to null) 
		 * @return Object json
		 **/		
		function createGroup($name, $kind, $desc = null, $slug = null) {
			
			$params = array('name' => $name, 
											'kind' => $kind, 
											'description' => $desc, 
											'slug' => $slug);
			
			return $this->methodCall('/groups/create.json', 'post', $params);
		}
		
		/**
		 * Retrieve particular group
		 * @param Integer $group_id
		 * @return Object json
		 **/
		function getGroupById($group_id) {
				return $this->methodCall('/groups/'.$group_id.'.json', 'get');
		}
		
		/**
		 * Join a particular group 
		 * @param Integer $group_id 
		 * @return Object json
		 **/
		function joinGroup($group_id) {
			$params = array('group_id' => sprintf('%d', $group_id));
			return $this->methodCall('/groups/'.$group_id.'/join.json', 'post', $params);
		}
		
		/**
		 * Join a private group
		 * @param Integer $group_id
		 * @return Object json
		 **/
		function joinPrivateGroup($group_id) {
			$params = array('group_id' => sprintf('%d', $group_id));
			return $this->methodCall('/groups/'.$group_id.'/request.json', 'post', $params);
			
		}
		
		/**
		 * Leave a particular group
		 * @param Integer $group_id 
		 * @return Object json
		 **/
		function leaveGroup($group_id) {
			$params = array('group_id' => sprintf('%d', $group_id));
			return $this->methodCall('/groups/'.$params['group_id'].'/leave.json', 'post', $params);
			
		}
		
		/**
		 * Retrieve the members of a particular group
		 * @param Integer $group_id 
		 * @param String $filter (optional parameter. 'admin' filters member list for admins of the group)
		 * @return Object json
		 **/
		function getMembersByGroup($group_id, $filter = null) {
			$params = array('filter' => sprintf('%s', $filter));
			return $this->methodCall('/groups/'.$group_id.'/members.json', 'get', $params);
			
		}
		
		/**
		 * Retrieve the members of a group that's currently online
		 * @param Integer $group_id 
		 * @return Object json
		 **/
		function getMembersOnlineByGroup($group_id) {
			return $this->methodCall('/groups/'.$group_id.'/online.json', 'get');
		}
		
		/**
		 * Mark all messages as read for a particular group		 
		 * @param Integer $group_id 
		 * @return Object json
		 **/
		function markReadByGroup($group_id) {
			return $this->methodCall('/groups/'.$group_id.'/mark_read.json', 'get');
		}
		
		/**
		 * Retrieve the topics for a particular group		 
		 * @param Integer $group_id 
		 * @param Integer $until_id
		 * @return Object json
		 **/
		function getTopicsByGroup($group_id, $until = null) {
			$params['until_id'] = $until;
			return $this->methodCall('/groups/'.$group_id.'/topics.json', 'get', $params);
		}
		
		/**
		 * Create a new topic for a particular group		 
		 * @param Integer $group_id
		 * @param String $name
		 * @return Object json
		 **/
		function createTopic($group_id, $name) {
			$params['name'] = $name;
			return $this->methodCall('/groups/'.$group_id.'/topics/create.json', 'post', $params);
		}
		
		// Topics
				
		/**
		 * Retrieve details for a given topic		 
		 * @param Integer $topic_id 
		 * @return Object json
		 **/
		function getTopic($topic_id) {
			return $this->methodCall('/topics/'.$topic_id.'.json', 'get', $params);
		}
		
		/**
		 * Delete a given topic		 
		 * @param Integer $topic_id 
		 * @return Object json
		 **/
		function deleteTopic($topic_id) {
			return $this->methodCall('/topics/'.$topic_id.'/delete.json', 'post');
		}
		
		/**
		 * Mark messages read for a given topic		 
		 * @param Integer $topic_id 
		 * @return Object json
		 **/
		function markReadByTopicId($topic_id) {
			return $this->methodCall('/topics/'.$topic_id.'/mark_read.json', 'post');
		}
		
		/**
		 * Retrieve messages for a given topic		 
		 * @param Integer $topic_id 
		 * @param Integer $until_id 
		 * @param Boolean $mark_read
		 * @return Object json
		 **/
		function getMessagesByTopicId($topic_id, $until_id = null, $mark_read = false) {
			$params = array(
				'until_id' => $until_id,
				'mark_read' => $mark_read
			);
			return $this->methodCall('/topics/'.$topic_id.'/messages.json', 'get', $params);
		}
		
		/**
		 * Create a new message	 
		 * @param Integer $topic_id 
		 * @param String $message
		 * @return Object json
		 **/
		function createMessage($topic_id, $message) {
			$params['message'] = vsprintf('%s', $message);
			return $this->methodCall('/topics/'.$topic_id.'/messages/create.json', 'post', $params);
		}
		
		//  Messages
		
		/**
		 * Star message	 
		 * @param Integer $message_id
		 * @return Object json
		 **/
		function starMessage($message_id) {
			return $this->methodCall('/messages/'.$message_id.'/create.json', 'post');
		}
		
		/**
		 * Delete message	 
		 * @param Integer $message_id
		 * @return Object json
		 **/
		function deleteMessage($message_id) {
			return $this->methodCall('/messages/'.$message_id.'/delete.json', 'post');
		}
		
		// Users
		
		/**
		 * retrieve user details 
		 * @param Integer $user_id
		 * @return Object json
		 **/
		function getUser($user_id) {
			return $this->methodCall('/users/'.$user_id.'.json', 'get');
		}
		
		
		
		function methodCall($convore_method, $action, $params = null, $auth_required = true) {
			$ch = curl_init();
			$request = sprintf($this->base_url.'%s', $convore_method);
			
			if($action == 'get') {
				curl_setopt($ch, CURLOPT_HTTPGET, true);
				
				if(isset($params)) {
					$request = $request.'?'.http_build_query($params);
				}
			}
			
			curl_setopt($ch, CURLOPT_URL, $request);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERPWD, $this->credentials);
			
			if ($action == 'post') {
				curl_setopt($ch, CURLOPT_POST, 1);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
			}
			
				$convore_data = curl_exec($ch);				
				$this->http_response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				return json_decode($convore_data);
				
		}		
	}
	
?>