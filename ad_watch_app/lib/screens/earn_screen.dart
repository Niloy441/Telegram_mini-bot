import 'package:ad_watch_app/models/user_model.dart';
import 'package:ad_watch_app/screens/ad_view_screen.dart';
import 'package:ad_watch_app/services/user_service.dart';
import 'package:ad_watch_app/widgets/weekly_activity_chart.dart';
import 'package:flutter/material.dart';
import 'package:charts_flutter/flutter.dart' as charts;

class EarnScreen extends StatefulWidget {
  const EarnScreen({super.key});

  @override
  State<EarnScreen> createState() => _EarnScreenState();
}

class _EarnScreenState extends State<EarnScreen> {
  final UserService _userService = UserService();
  UserModel? _user;
  List<charts.Series<Task, String>>? _seriesList;

  @override
  void initState() {
    super.initState();
    _loadUserData();
  }

  Future<void> _loadUserData() async {
    // Replace with actual user ID
    final user = await _userService.getUser('some_user_id');
    setState(() {
      _user = user;
      _seriesList = _createSampleData();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _user == null
          ? const Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    "Today's Progress",
                    style: Theme.of(context).textTheme.headline6,
                  ),
                  const SizedBox(height: 8),
                  LinearProgressIndicator(
                    value: _user!.adsWatched / 10, // Replace with daily ad limit
                  ),
                  const SizedBox(height: 24),
                  ElevatedButton(
                    onPressed: () async {
                      final result = await Navigator.push(
                        context,
                        MaterialPageRoute(builder: (context) => const AdViewScreen()),
                      );
                      if (result == true) {
                        _loadUserData();
                      }
                    },
                    child: const Text('Watch Ad & Earn'),
                  ),
                  const SizedBox(height: 24),
                  Text(
                    'Weekly Activity',
                    style: Theme.of(context).textTheme.headline6,
                  ),
                  const SizedBox(height: 16),
                  SizedBox(
                    height: 200,
                    child: WeeklyActivityChart(
                      _seriesList!,
                      animate: true,
                    ),
                  ),
                ],
              ),
            ),
    );
  }

  static List<charts.Series<Task, String>> _createSampleData() {
    final data = [
      Task('Mon', 5),
      Task('Tue', 7),
      Task('Wed', 2),
      Task('Thu', 9),
      Task('Fri', 4),
      Task('Sat', 6),
      Task('Sun', 3),
    ];

    return [
      charts.Series<Task, String>(
        id: 'Tasks',
        colorFn: (_, __) => charts.MaterialPalette.blue.shadeDefault,
        domainFn: (Task task, _) => task.day,
        measureFn: (Task task, _) => task.tasks,
        data: data,
      )
    ];
  }
}
