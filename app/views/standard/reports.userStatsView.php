<div class="page-header">
    <h1 class="text-center">Stats for <?= $data['user'] ?></h1>
</div>

<div class="row">
    <div class="col-lg-10 col-lg-offset-1">
        <canvas id="myChart"></canvas>
    </div>
</div>


<table class="table">
    <thead>
        <tr>
            <th></th>
            <th>Last 30 Days</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>System Files Uploaded</td>
            <td><?= $data['sysFiles30'] ?></td>
            <td><?= $data['sysFiles'] ?></td>
        </tr>
        <tr>
            <td>Customer Backups Uploaded</td>
            <td><?= $data['custFiles30'] ?></td>
            <td><?= $data['custFiles'] ?></td>
        </tr>
        <tr>
            <td>Customer Files (non backups) Uploaded</td>
            <td><?= $data['custNonBak30'] ?></td>
            <td><?= $data['custNonBak'] ?></td>
        </tr>
        <tr>
            <td>Customer Notes Created/Updated</td>
            <td><?= $data['custNotes30'] ?></td>
            <td><?= $data['custNotes'] ?></td>
        </tr>
        <tr>
            <td>Tech Tips Created</td>
            <td><?= $data['techTips30'] ?></td>
            <td><?= $data['techTips'] ?></td>
        </tr>
        <tr>
            <td>Tech Tip Comments</td>
            <td><?= $data['tipComments30'] ?></td>
            <td><?= $data['tipComments'] ?></td>
        </tr>
    </tbody>
</table>



<script src="/source/lib/chart.js/Chart.bundle.min.js"></script>
<script>
    
var ctx = document.getElementById("myChart");
var myChart = new Chart(ctx, 
{
    type: 'line',
    data: 
    {
        labels: [<?= $data['months'] ?>],
        datasets: [{
            label: 'Times Logged In Each Month',
            data: [<?= $data['loginData'] ?>],
            fill: false,
            backgroundColor: '#FF0000',
            borderColor: '#FF0000'
        }]
    }
});
</script>
