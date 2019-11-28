
-- 处理sn_info.bid等于sn_batch_info.id

update `sn_info` INNER JOIN

(SELECT CONCAT(m.`short`,c.`short`,t.`short`,a.`batch_year`,a.`batch_no`) AS n_bid,a.`id` as n_id FROM sn_batch_info a 
    LEFT JOIN `sn_manufacture_short_info` m ON a.`m_id` = m.`mid`
    LEFT JOIN `sn_device_type_short_info` t ON a.`h_id` = t.`device_type_id`
    LEFT JOIN `device_type` dt ON t.`device_type_id` = dt.`id`
    LEFT JOIN `sn_category_short_info` c ON dt.`category_id` = c.`category_id`
 WHERE a.`h_type`=1

UNION ALL

SELECT CONCAT(m.`short`,'RC',t.`short`,a.`batch_year`,a.`batch_no`) AS n_bid,a.`id` as n_id FROM sn_batch_info a 
    LEFT JOIN `sn_manufacture_short_info` m ON a.`m_id` = m.`mid`
    LEFT JOIN `sn_remote_short_info` t ON a.`h_id` = t.`remote_type_id`
    LEFT JOIN `remote_type` dt ON t.`remote_type_id` = dt.`id`
 WHERE a.`h_type`=2) b ON sn_info.`bid` = b.n_bid

 set sn_info.`bid`=b.n_id