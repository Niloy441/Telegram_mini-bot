import 'package:flutter/material.dart';
import 'package:charts_flutter/flutter.dart' as charts;

class WeeklyActivityChart extends StatelessWidget {
  final List<charts.Series<dynamic, String>> seriesList;
  final bool animate;

  const WeeklyActivityChart(this.seriesList, {super.key, this.animate = false});

  @override
  Widget build(BuildContext context) {
    return charts.BarChart(
      seriesList,
      animate: animate,
    );
  }
}

class Task {
  final String day;
  final int tasks;

  Task(this.day, this.tasks);
}
