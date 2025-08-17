import 'dart:async';

import 'package:ad_watch_app/services/user_service.dart';
import 'package:flutter/material.dart';

class AdViewScreen extends StatefulWidget {
  const AdViewScreen({super.key});

  @override
  State<AdViewScreen> createState() => _AdViewScreenState();
}

class _AdViewScreenState extends State<AdViewScreen> {
  int _timerValue = 15;
  Timer? _timer;
  bool _isTimerFinished = false;
  final UserService _userService = UserService();

  @override
  void initState() {
    super.initState();
    _startTimer();
  }

  void _startTimer() {
    _timer = Timer.periodic(const Duration(seconds: 1), (timer) {
      if (_timerValue == 0) {
        setState(() {
          _isTimerFinished = true;
        });
        _timer?.cancel();
      } else {
        setState(() {
          _timerValue--;
        });
      }
    });
  }

  @override
  void dispose() {
    _timer?.cancel();
    super.dispose();
  }

  Future<void> _onRewardClicked() async {
    try {
      await _userService.watchAd('some_user_id'); // Replace with actual user ID
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Reward received!')),
      );
      Navigator.pop(context, true); // Return true to indicate success
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Failed to get reward: $e')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Watch Ad - $_timerValue s'),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Text(
              'Ad from Monetag network', // Placeholder for the ad
              style: TextStyle(fontSize: 20),
            ),
            const SizedBox(height: 40),
            if (_isTimerFinished)
              Column(
                children: [
                  ElevatedButton(
                    onPressed: _onRewardClicked,
                    child: const Text('Click to get the reward!'),
                  ),
                  const SizedBox(height: 16),
                  TextButton(
                    onPressed: () {
                      Navigator.pop(context);
                    },
                    child: const Text('Continue'),
                  ),
                ],
              ),
          ],
        ),
      ),
    );
  }
}
