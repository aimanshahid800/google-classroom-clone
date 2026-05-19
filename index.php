<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// -----------------------------------------------
// IS WEEK KI DATES CALCULATE KARO
// -----------------------------------------------

// Aaj ka din
$today = date('Y-m-d');

// Is week ka Monday dhundho
$day_of_week = date('N'); // 1=Monday, 7=Sunday
$monday = date('Y-m-d', strtotime("-" . ($day_of_week - 1) . " days"));

// Week ke 7 din banao
$week_days = [];
for ($i = 0; $i < 7; $i++) {
    $week_days[] = date('Y-m-d', strtotime($monday . " +$i days"));
}

$week_start = $week_days[0]; // Monday
$week_end   = $week_days[6]; // Sunday

// -----------------------------------------------
// IS WEEK KI ASSIGNMENTS FETCH KARO
// -----------------------------------------------
$cal_query = "
    SELECT a.id, a.title, a.due_date, c.name AS class_name
    FROM assignments a
    JOIN classes c ON a.class_id = c.id
    JOIN class_members cm ON cm.class_id = c.id
    WHERE cm.user_id = $user_id
    AND a.due_date BETWEEN '$week_start' AND '$week_end'
    ORDER BY a.due_date ASC
";
$cal_result = mysqli_query($conn, $cal_query);

// Assignments ko date ke hisaab se group karo
$assignments_by_date = [];
while ($row = mysqli_fetch_assoc($cal_result)) {
    $assignments_by_date[$row['due_date']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar — Google Classroom</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .calendar-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 0 20px;
        }
        h1 {
            font-size: 22px;
            color: #202124;
            margin-bottom: 8px;
        }
        .week-label {
            font-size: 14px;
            color: #5f6368;
            margin-bottom: 20px;
        }
        /* 7 column grid for week */
        .week-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
        }
        .day-col {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            min-height: 180px;
            padding: 10px 8px;
            background: white;
        }
        .day-col.today {
            border-color: #1a73e8;
            background: #e8f0fe;
        }
        .day-header {
            text-align: center;
            font-size: 12px;
            color: #5f6368;
            margin-bottom: 4px;
        }
        .day-number {
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            color: #202124;
            margin-bottom: 8px;
        }
        .day-col.today .day-number {
            background: #1a73e8;
            color: white;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-size: 16px;
        }
        .event-chip {
            background: #1a73e8;
            color: white;
            border-radius: 4px;
            padding: 3px 6px;
            font-size: 11px;
            margin-bottom: 4px;
            cursor: pointer;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .event-chip:hover {
            background: #1557b0;
        }
        .no-event {
            font-size: 11px;
            color: #ccc;
            text-align: center;
            margin-top: 20px;
        }

        /* Mobile responsive */
        @media (max-width: 600px) {
            .week-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="calendar-container">
    <h1>Calendar</h1>
    <p class="week-label">
        Week of <?php echo date("d M", strtotime($week_start)); ?> — <?php echo date("d M Y", strtotime($week_end)); ?>
    </p>

    <!-- Week Grid -->
    <div class="week-grid">
        <?php
        $day_names = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        foreach ($week_days as $index => $date):
            $is_today = ($date == $today);
            $day_num  = date('j', strtotime($date)); // e.g. 19
        ?>
        <div class="day-col <?php echo $is_today ? 'today' : ''; ?>">
            <!-- Day name: Mon, Tue... -->
            <div class="day-header"><?php echo $day_names[$index]; ?></div>

            <!-- Day number -->
            <div class="day-number"><?php echo $day_num; ?></div>

            <!-- Us din ki assignments -->
            <?php if (isset($assignments_by_date[$date])): ?>
                <?php foreach ($assignments_by_date[$date] as $assignment): ?>
                    <div class="event-chip" title="<?php echo htmlspecialchars($assignment['class_name']); ?>">
                        <?php echo htmlspecialchars($assignment['title']); ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-event">—</div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
