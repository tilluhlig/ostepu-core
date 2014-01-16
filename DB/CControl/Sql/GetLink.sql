select 
    CL.CL_id,
    CL.CL_name,
    CL.CL_relevanz,
        CL.CO_id_target,
            CL.CO_id_owner,
    CO.CO_address as CL_address,
    CL.CO_id_target
from
    ComponentLinkage CL
        join
    Component CO ON CO.CO_id = CL.CO_id_owner
where
    CL_id = '$linkid' or CL_name = '$linkid'