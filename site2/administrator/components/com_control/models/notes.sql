SELECT am.acl_id, garom.*, axogm.*, axog.value
FROM jos_core_acl_aco_map AS am
INNER JOIN jos_core_acl_acl AS acl ON acl.id = am.acl_id
INNER JOIN jos_core_acl_aro_groups_map AS agm ON agm.acl_id = am.acl_id
LEFT JOIN jos_core_acl_axo_groups_map AS axogm ON axogm.acl_id = am.acl_id
INNER JOIN jos_core_acl_axo_groups AS axog ON axog.id = axogm.group_id
INNER JOIN jos_core_acl_groups_aro_map AS garom ON garom.group_id = agm.group_id
WHERE am.section_value = 'com_documents'
  AND am.value = 'download'
  AND acl.enabled = 1
  AND acl.allow = 1

  SELECT GROUP_CONCAT( axog.value SEPARATOR ',')
FROM jos_core_acl_aco_map AS am
INNER JOIN jos_core_acl_acl AS acl ON acl.id = am.acl_id
INNER JOIN jos_core_acl_aro_groups_map AS agm ON agm.acl_id = am.acl_id
LEFT JOIN jos_core_acl_axo_groups_map AS axogm ON axogm.acl_id = am.acl_id
INNER JOIN jos_core_acl_axo_groups AS axog ON axog.id = axogm.group_id
INNER JOIN jos_core_acl_groups_aro_map AS garom ON garom.group_id = agm.group_id
WHERE am.section_value = 'com_documents'
  AND am.value = 'download'
  AND acl.enabled = 1
  AND acl.allow = 1
