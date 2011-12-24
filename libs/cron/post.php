<?php

class Cron_Post extends Cron_Abstract 
{
	const MAX_LINK_DELETIONS = 200;
	
	public function delete_unused_urls() {
		Database::begin();
		
		try {
			
			Database::sql('DELETE FROM post_link WHERE 
				post_id NOT IN (SELECT id FROM post)');
				
			Database::sql('DELETE FROM post_update_link WHERE 
				update_id NOT IN (SELECT id FROM post_update)');	

			Database::sql('DELETE FROM post_link_url WHERE 
				link_id NOT IN (SELECT id FROM post_link)');
				
			Database::sql('DELETE FROM post_update_link_url WHERE 
				link_id NOT IN (SELECT id FROM post_update_link)');				
			
			Database::sql('DELETE FROM post_url WHERE 
				id NOT IN (SELECT url_id FROM post_link_url) AND 
				id NOT IN (SELECT url_id FROM post_update_link_url)');
		
			$count = Database::count_affected();
				
			if ($count > self::MAX_LINK_DELETIONS) {

				Database::rollback();
				throw new Error_Cron('Too many post urls pending for deletion');				
			}
			
			Database::commit();
		} catch (PDOException $e) {
			
			Database::rollback();
			throw new Error_Cron('Error with database, while deleting post urls');
		}
	}
}
