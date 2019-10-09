<?php
class MG_Megaforum_ForumController extends Mage_Core_Controller_Front_Action{

    public function rssAction()
		{
			
				echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
				echo "<rss xmlns:content=\"http://purl.org/rss/1.0/modules/content/\" version=\"2.0\">";
				echo "<channel>";
				echo "<title><![CDATA[Mega Forum]]></title>";
				echo "<link>".Mage::getUrl('megaforum/index/index')."</link>";
				echo "<description><![CDATA[Mega Forum]]></description>";
				
			$forum = Mage::getModel('megaforum/forum')->getCollection(); 
			
			foreach($forum as $forums){
			
			 $forumName = $forums->getForumName();
			 $topic = Mage::getModel('megaforum/topic')->getCollection()->addFieldToFilter("forum_id",$forums->getForumId()); 
		
			
		    foreach($topic as $topics){
			 
			 $topicName = $topics->getTopicName();
			 $topicDesc = $topics->getMessage();
			 $topicCreatedAt = $topics->getCreatedAt();
			 $post = Mage::getModel('megaforum/post')->getCollection()->addFieldToFilter("topic_id",$topics->getTopicId());
			
			foreach($post as $posts){
				
				$postId = $posts->getPostId();
			
				echo "<item><title><![CDATA[Forum "."'$forumName'".", Topic "."'$topicName'".", Post ID "."'$postId'"."]]></title>
				 <link>".Mage::getUrl('megaforum/index/post')."id/".$postId."</link>
				 <description><![CDATA[<p>".$topicDesc."</p>]]></description>
				 <pubDate>".$topicCreatedAt."</pubDate></item>";
			
			} 
			}
			}
			
			 echo "</channel></rss>";
		}
	
	
}

