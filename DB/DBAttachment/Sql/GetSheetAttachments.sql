select 
    A.A_id,
    A.E_id,
    F.F_id,
    F.F_displayName,
    F.F_address,
    F.F_timeStamp,
    F.F_fileSize,
    F.F_hash
from
    Exercise E
        join
    (Attachment A
    left join File F ON F.F_id = A.F_id) ON E.E_id = A.E_id
where
    E.ES_id = '$esid'