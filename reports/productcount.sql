DECLARE @startdate SMALLDATETIME, @enddate SMALLDATETIME
SET @startdate = '01/01/2018 00:00' /* Update Start Date Here */
SET @enddate = '12/30/2018 23:59'  /* Update End Date Here */
SELECT
od.productcode,
od.productname,
Sum(od.quantity) AS QuantitySold,
Sum(od.totalprice) AS Revenue
FROM orderdetails AS od WITH (NOLOCK)
INNER JOIN orders AS o WITH (NOLOCK) ON o.orderid = od.orderid
WHERE o.orderstatus <> 'Cancelled'
AND o.orderstatus <> 'Returned'
AND od.productcode <> 'shipping'
AND od.productcode NOT LIKE 'DSC-%'
AND o.orderdate BETWEEN @startdate AND @enddate
GROUP BY
od.productcode
ORDER BY QuantitySold ASC