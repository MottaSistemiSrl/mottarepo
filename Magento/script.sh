#!/bin/sh
# Script di deploy creato da Camillo - Motta Sistemi - mottasistemi.seo@gmail.com
#
# Ad ogni modifica dello script ricordarsi di modificare i permessi a questo file quando è su server prima di avviarlo:  chmod +x script.sh
#
echo "***********************************************************"
echo "Avvio script di deploy verso produzione burdastyle.it - - -"
echo "***********************************************************"

TABELLE="adminnotification_inbox am_customerattr_relation am_customerattr_relation_details am_ogrid_order_item am_ogrid_order_item_product am_shopby_filter am_shopby_page am_shopby_range am_shopby_value amasty_amorderattach_field amasty_amorderattach_order_field amasty_amrma_comment amasty_amrma_comment_file amasty_amrma_item amasty_amrma_request amasty_amrma_status amasty_amrma_status_label amasty_amrma_status_template amasty_flag amasty_flag_column amasty_order_flag amm_product api2_acl_attribute api2_acl_role api2_acl_rule api2_acl_user api_assert api_role api_rule api_session api_user audioVideos auguria_sliders auguria_sliders_categories auguria_sliders_pages auguria_sliders_stores aw_blog aw_blog_cat aw_blog_cat_store aw_blog_comment aw_blog_post_cat aw_blog_related aw_blog_store aw_blog_tags bitbull_bancasellapro_token captcha_log catalog_category_anc_categs_index_idx catalog_category_anc_categs_index_tmp catalog_category_anc_products_index_idx catalog_category_anc_products_index_tmp catalog_category_entity catalog_category_entity_datetime catalog_category_entity_decimal catalog_category_entity_int catalog_category_entity_text catalog_category_entity_varchar catalog_category_flat_store_1 catalog_category_product catalog_category_product_index catalog_category_product_index_enbl_idx catalog_category_product_index_enbl_tmp catalog_category_product_index_idx catalog_category_product_index_tmp catalog_compare_item catalog_eav_attribute catalog_product_bundle_option catalog_product_bundle_option_value catalog_product_bundle_price_index catalog_product_bundle_selection catalog_product_bundle_selection_price catalog_product_bundle_stock_index catalog_product_enabled_index catalog_product_entity catalog_product_entity_datetime catalog_product_entity_decimal catalog_product_entity_gallery catalog_product_entity_group_price catalog_product_entity_int catalog_product_entity_media_gallery catalog_product_entity_media_gallery_value catalog_product_entity_text catalog_product_entity_tier_price catalog_product_entity_varchar catalog_product_flat_1 catalog_product_index_eav catalog_product_index_eav_decimal catalog_product_index_eav_decimal_idx catalog_product_index_eav_decimal_tmp catalog_product_index_eav_idx catalog_product_index_eav_tmp catalog_product_index_group_price catalog_product_index_price catalog_product_index_price_bundle_idx catalog_product_index_price_bundle_opt_idx catalog_product_index_price_bundle_opt_tmp catalog_product_index_price_bundle_sel_idx catalog_product_index_price_bundle_sel_tmp catalog_product_index_price_bundle_tmp catalog_product_index_price_cfg_opt_agr_idx catalog_product_index_price_cfg_opt_agr_tmp catalog_product_index_price_cfg_opt_idx catalog_product_index_price_cfg_opt_tmp catalog_product_index_price_downlod_idx catalog_product_index_price_downlod_tmp catalog_product_index_price_final_idx catalog_product_index_price_final_tmp catalog_product_index_price_idx catalog_product_index_price_opt_agr_idx catalog_product_index_price_opt_agr_tmp catalog_product_index_price_opt_idx catalog_product_index_price_opt_tmp catalog_product_index_price_tmp catalog_product_index_tier_price catalog_product_index_website catalog_product_link catalog_product_link_attribute catalog_product_link_attribute_decimal catalog_product_link_attribute_int catalog_product_link_attribute_varchar catalog_product_link_type catalog_product_option catalog_product_option_price catalog_product_option_title catalog_product_option_type_price catalog_product_option_type_title catalog_product_option_type_value catalog_product_relation catalog_product_super_attribute catalog_product_super_attribute_label catalog_product_super_attribute_pricing catalog_product_super_link catalog_product_website cataloginventory_stock cataloginventory_stock_item cataloginventory_stock_status cataloginventory_stock_status_idx cataloginventory_stock_status_tmp catalogrule catalogrule_affected_product catalogrule_customer_group catalogrule_group_website catalogrule_product catalogrule_product_price catalogrule_website checkout_agreement checkout_agreement_store cms_block cms_block_store cms_page cms_page_store companies countries coupon_aggregated coupon_aggregated_order coupon_aggregated_updated cron_schedule dataflow_batch dataflow_batch_export dataflow_batch_import dataflow_import_data dataflow_profile dataflow_profile_history dataflow_session design_change directory_country directory_country_format directory_country_region directory_country_region_name directory_currency_rate downloadable_link downloadable_link_price downloadable_link_purchased downloadable_link_purchased_item downloadable_link_title downloadable_sample downloadable_sample_title eav_attribute eav_attribute_group eav_attribute_label eav_attribute_option eav_attribute_option_value eav_attribute_set eav_entity eav_entity_attribute eav_entity_datetime eav_entity_decimal eav_entity_int eav_entity_store eav_entity_text eav_entity_type eav_entity_varchar eav_form_element eav_form_fieldset eav_form_fieldset_label eav_form_type eav_form_type_entity forum forumstore forumuser forumusertype gift_message glossary glossary_store gomage_social_entity importexport_importdata index_event index_process index_process_event industries iwd_notification liveTranslations mailup_filter_hints mailup_log mailup_sync mailup_sync_jobs newsletter_problem newsletter_queue newsletter_queue_link newsletter_queue_store_link newsletter_subscriber newsletter_template notificationtemplate oauth_consumer oauth_nonce oauth_token ordersexporttool_attributes ordersexporttool_profiles paypal_cert paypal_payment_transaction paypal_settlement_report paypal_settlement_report_row paypalauth_customer persistent_session plans poll poll_answer poll_store poll_vote post privatemsg product_alert_price product_alert_stock productcomment productcomment_lck rating rating_entity rating_option rating_option_vote rating_option_vote_aggregated rating_store rating_title review review_detail review_entity review_entity_summary review_status review_store rewardpoints_account rewardpoints_catalogrules rewardpoints_flat_account rewardpoints_pointrules rewardpoints_referral rewardpoints_referralrules rewardpoints_rule salesrule salesrule_coupon salesrule_coupon_usage salesrule_customer salesrule_customer_group salesrule_label salesrule_product_attribute salesrule_website sendfriend_log shipping_tablerate sitemap sl_mediaserver_slides sl_mediaserver_slides_times sl_mediaserver_videos sl_numerazione_documenti tag tag_properties tag_relation tag_summary tax_calculation tax_calculation_rate tax_calculation_rate_title tax_calculation_rule tax_class tax_order_aggregated_created tax_order_aggregated_updated"

echo "***********************************************************"
echo "Dump del database . . . . . . . . . . . . . . . . . . . . . "
echo "***********************************************************"

# Esporto le tabelle del db
#mysqldump -uburdastyle_stage -pYzc0*x00123kjsdf_0skd --no-create-info --insert-ignore RaffiBurdaStyle_stg ${TABELLE} | gzip > db_burda.sql.gz
mysqldump -uburdastyle_stage -pYzc0*x00123kjsdf_0skd RaffiBurdaStyle_stg ${TABELLE} | gzip > db_burda.sql.gz

echo "***********************************************************"
echo "Aggiornamento registro  . . . . . . . . . . . . . . . . . . "
echo "***********************************************************"

# Aggiorno registro git
git status
git add .
git commit -m "commit da script"
git push

echo "***********************************************************"
echo "Avvio deploy  . . . . . . . . . . . . . . . . . . . . . . . "
echo "***********************************************************"

#avvio il comando di deploy verso produzione
cap production deploy

echo "***********************************************************"
echo " Clean delle cache . . . . . . . . . . . . . . . . . . . . "
echo "***********************************************************"

rm -rf var/cache/*

echo "***********************************************************"
echo " Re-index degli indici . . . . . . . . . . . . . . . . . . "
echo "***********************************************************"

php shell/indexer.php --reindexall

echo "***********************************************************"
echo "Deploy Avvenuto con successo  . . . . . . . . . . . . . . . "
echo "***********************************************************"
