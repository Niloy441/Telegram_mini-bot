import 'package:ad_watch_app/models/user_model.dart';
import 'package:ad_watch_app/services/api_service.dart';

class UserService {
  final ApiService _apiService = ApiService();

  Future<UserModel> getUser(String uid) async {
    final data = await _apiService.get('users/$uid');
    return UserModel(
      uid: data['uid'],
      name: data['name'],
      username: data['username'],
      profilePictureUrl: data['profilePictureUrl'],
      balance: data['balance'],
      adsWatched: data['adsWatched'],
      referrals: data['referrals'],
    );
  }

  Future<void> updateUser(UserModel user) async {
    await _apiService.post('users/${user.uid}', {
      'name': user.name,
      'username': user.username,
      'profilePictureUrl': user.profilePictureUrl,
      'balance': user.balance,
      'adsWatched': user.adsWatched,
      'referrals': user.referrals,
    });
  }

  Future<void> watchAd(String uid) async {
    await _apiService.post('users/$uid/watch-ad', {});
  }

  Future<void> withdraw(String uid, String method, double amount, String address) async {
    await _apiService.post('withdrawals', {
      'uid': uid,
      'method': method,
      'amount': amount,
      'address': address,
    });
  }
}
