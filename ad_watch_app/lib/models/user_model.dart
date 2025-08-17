class UserModel {
  final String uid;
  final String name;
  final String username;
  final String profilePictureUrl;
  double balance;
  int adsWatched;
  int referrals;

  UserModel({
    required this.uid,
    required this.name,
    required this.username,
    required this.profilePictureUrl,
    this.balance = 0.0,
    this.adsWatched = 0,
    this.referrals = 0,
  });
}
