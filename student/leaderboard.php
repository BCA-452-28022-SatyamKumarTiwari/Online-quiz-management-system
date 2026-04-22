<?php include 'student-header.php'; ?>

<h2>Leaderboard</h2>

<table>
<tr><th>Rank</th><th>Name</th><th>Score</th></tr>

<?php
$res=$conn->query("
SELECT users.full_name, MAX(score) as best
FROM attempts
JOIN users ON users.id=attempts.user_id
GROUP BY user_id
ORDER BY best DESC
LIMIT 10
");

$i=1;
while($r=$res->fetch_assoc()){
echo "<tr>
<td>$i</td>
<td>{$r['full_name']}</td>
<td>{$r['best']}</td>
</tr>";
$i++;
}
?>
</table>

<?php include 'student-footer.php'; ?>
