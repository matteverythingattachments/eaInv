SELECT
o.orderid,
o.lastmodified,
o.orderdate,
o.orderstatus,
o.paymentamount,
o.shipped,
o.shipstate,
o.total_payment_received,
od.productcode,
od.productname
FROM orders AS o
INNER JOIN orderdetails AS od ON od.orderid = o.orderid
WHERE o.orderstatus <> 'Cancelled'
AND o.orderstatus <> 'Returned'
AND od.productcode <> 'shipping'
AND od.productcode NOT LIKE 'DSC-%'
AND o.orderdate >= DATEADD(day, -1, GETDATE())
ORDER BY o.orderdate ASC
