<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Helper;
use Session;
use App\User;
use DataTables;
use App\Http\Controllers\ActivityController as Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class UsersController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

    	return view('admin.users.index');
    }

    public function getMemberActive(){
    	$data = User::where(['is_active' => 1, 'level' => 2])->orderBy('created_at', 'desc');

    	return DataTables::of($data)

            ->addColumn('nama', function ($data) {

                return $data->name;

            })

            ->addColumn('jekel', function ($data) {

                return $data->sex;

            })

            ->addColumn('username', function ($data) {

                return $data->username;

            })

            ->addColumn('email', function ($data) {

                return $data->email;

            })

            ->addColumn('tanggal', function ($data) {

                return date('d-m-Y H:i:s', strtotime($data->created_at));

            })

            ->addColumn('last_login_web', function ($data) {

                if (!is_null($data->login)) {
                    return date('d-m-Y H:i:s', strtotime($data->login));
                } else {
                    return null;
                }

            })

            ->addColumn('last_login_mobile', function ($data) {

                if (!is_null($data->login_mobile)) {
                    return date('d-m-Y H:i:s', strtotime($data->login_mobile));
                } else {
                    return null;
                }

            })

            ->addColumn('is_online', function ($data) {

                return Helper::userOnlineStatus(Crypt::encrypt($data->id));

            })

            ->addColumn('aksi', function ($data) {

                return '<p class="text-center"><a href="'.url('/admin/users/suspend/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan menangguhkan member ini?'.'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-times"></i> Suspend</a>&nbsp;<a href="'.url('/admin/users/detail/'.Crypt::encrypt($data->id)).'" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-info"></i> Detail</a></p>';

            })

            ->rawColumns(['nama', 'jekel', 'username', 'email', 'tanggal', 'is_online', 'aksi'])

            ->make(true);
    }

    public function getMemberNonActive(){
    	$data = User::where(['is_active' => 0, 'level' => 2])->orderBy('created_at', 'desc');

    	return DataTables::of($data)

            ->addColumn('nama', function ($data) {

                return $data->name;

            })

            ->addColumn('jekel', function ($data) {

                return $data->sex;

            })

            ->addColumn('username', function ($data) {

                return $data->username;

            })

            ->addColumn('email', function ($data) {

                return $data->email;

            })

            ->addColumn('tanggal', function ($data) {

                return date('d-m-Y H:i:s', strtotime($data->created_at));

            })

            ->addColumn('aksi', function ($data) {

                return '<p class="text-center"><a href="'.url('/admin/users/active/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan mengaktifkan member ini?'.'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-check"></i> Aktif</a>&nbsp;<a href="'.url('/admin/users/detail/'.Crypt::encrypt($data->id)).'" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-info"></i> Detail</a></p>';

            })

            ->rawColumns(['nama', 'jekel', 'username', 'email', 'tanggal', 'aksi'])

            ->make(true);
    }

    public function getMemberSuspend(){
        $data = User::where(['is_active' => 2, 'level' => 2])->orderBy('created_at', 'desc');

        return DataTables::of($data)

            ->addColumn('nama', function ($data) {

                return $data->name;

            })

            ->addColumn('jekel', function ($data) {

                return $data->sex;

            })

            ->addColumn('username', function ($data) {

                return $data->username;

            })

            ->addColumn('email', function ($data) {

                return $data->email;

            })

            ->addColumn('tanggal', function ($data) {

                return date('d-m-Y H:i:s', strtotime($data->created_at));

            })

            ->addColumn('last_login_web', function ($data) {

                if (!is_null($data->login)) {
                    return date('d-m-Y H:i:s', strtotime($data->login));
                } else {
                    return null;
                }

            })

            ->addColumn('last_login_mobile', function ($data) {

                if (!is_null($data->login_mobile)) {
                    return date('d-m-Y H:i:s', strtotime($data->login_mobile));
                } else {
                    return null;
                }

            })

            ->addColumn('aksi', function ($data) {

                return '<p class="text-center"><a href="'.url('/admin/users/active/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan mengaktifkan member ini?'.'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-check"></i> Aktif</a>&nbsp;<a href="'.url('/admin/users/detail/'.Crypt::encrypt($data->id)).'" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-info"></i> Detail</a></p>';

            })

            ->rawColumns(['nama', 'jekel', 'username', 'email', 'tanggal', 'aksi'])

            ->make(true);
    }

    public function active($id = null)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

        DB::beginTransaction();
        try{
            User::where('id', $id)->update(['is_active' => 1]);
            $member = User::where('id', $id)->first()->email;
            Activity::log(Auth::user()->id, 'Activated Member', 'Activated Member', 'mengaktifkan member '.$member, null, Carbon::now('Asia/Jakarta'));
            DB::commit();
            return redirect()->back()->with('flash_message_success', 'Berhasil mengaktifkan member!');
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Gagal mengaktifkan member!');
        }
    }

    public function nonactive($id = null)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

        DB::beginTransaction();
        try{
            User::where('id', $id)->update(['is_active' => 0]);
            $member = User::where('id', $id)->first()->email;
            Activity::log(Auth::user()->id, 'Nonactivated Member', 'Nonactivated Member', 'menonaktifkan member '.$member, null, Carbon::now('Asia/Jakarta'));
            DB::commit();
            return redirect()->back()->with('flash_message_success', 'Berhasil menonaktifkan member!');
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Gagal menonaktifkan member!');
        }
    }

    public function suspend($id = null)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

        DB::beginTransaction();
        try{
            User::where('id', $id)->update(['is_active' => 2]);
            $member = User::where('id', $id)->first()->email;
            Activity::log(Auth::user()->id, 'Nonactivated Member', 'Suspend Member', 'menangguhkan member '.$member, null, Carbon::now('Asia/Jakarta'));
            DB::commit();
            return redirect()->back()->with('flash_message_success', 'Member berhasil ditangguhkan!');
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Gagal menangguhkan member!');
        }
    }

    public function detail($id = null)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

        $member = User::where('id', $id)->first();
        $tgllahir = User::select('tgl_lahir')
            ->where('id', Auth::user()->id)->first();
        $date = [];
        $date = explode('-', $tgllahir->tgl_lahir);
        $day = $date[2];
        $month = $date[1];
        $year = $date[0];

        return view('admin.users.detail')->with(compact('member','day', 'month', 'year'));
    }

    public function updateNama(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

        DB::beginTransaction();
        try{
            $user = User::where('id', Auth::user()->id)->first();
            Activity::log(Auth::user()->id, 'Update', 'merubah nama pengguna', 'Diperbarui menjadi ' . $request->nama, 'Nama sebelumnya ' . $user->name, Carbon::now('Asia/Jakarta'));
            User::where('id', Auth::user()->id)->update([
                'name' => $request->nama
            ]);
            DB::commit();
            return redirect()->back()->with('flash_message_success', 'Nama berhasil diperbarui!');
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Nama gagal diperbarui!');
        }
    }

    public function updateEmail(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

        DB::beginTransaction();
        try{
            $check = User::where('email', $request->email)->count();
            if ($check > 0) {
                return redirect()->back()->with('flash_message_error', 'Gagal memperbarui email, email sudah digunakan!');
            } else {
                $user = User::where('id', Auth::user()->id)->first();
                Activity::log(Auth::user()->id, 'Update', 'merubah email', 'Diperbarui menjadi ' . $request->email, 'Email sebelumnya ' . $user->email, Carbon::now('Asia/Jakarta'));
                User::where('id', Auth::user()->id)->update([
                    'email' => $request->email
                ]);
                DB::commit();
                return redirect()->back()->with('flash_message_success', 'Email berhasil diperbarui!');
            }
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Email gagal diperbarui!');
        }
    }

    public function updateUsername(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

        DB::beginTransaction();
        try{
            $check = User::where('username', $request->username)->count();
            if ($check > 0) {
                return redirect()->back()->with('flash_message_error', 'Gagal memperbarui username, username sudah digunakan!');
            } else {
                $user = User::where('id', Auth::user()->id)->first();
                Activity::log(Auth::user()->id, 'Update', 'merubah username', 'Diperbarui menjadi ' . $request->username, 'Username sebelumnya ' . $user->username, Carbon::now('Asia/Jakarta'));
                User::where('id', Auth::user()->id)->update([
                    'username' => $request->username
                ]);
                DB::commit();
                return redirect()->back()->with('flash_message_success', 'Username berhasil diperbarui!');
            }
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Username gagal diperbarui!');
        }
    }

    public function updatePassword(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

        try {
            $id = Crypt::decrypt($request->id);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

        DB::beginTransaction();
        try{
            if ($request->newPassword == "" || $request->vernewPassword == ""){
                return redirect()->back()->with('flash_message_error', 'Lengkapi data!');
            }

            $pwd = User::where('id', $id)->first();

            if ($request->vernewPassword != $request->newPassword){
                return redirect()->back()->with('flash_message_error', 'Verifikasi kata sandi baru salah!');
            } else if ($request->vernewPassword == $request->newPassword){
                Activity::log(Auth::user()->id, 'Update', 'merubah kata sandi user "'.$pwd->username.'"', 'Kata sandi telah diperbarui', null, Carbon::now('Asia/Jakarta'));
                User::where('id', $id)->update([
                    'password' => bcrypt($request->newPassword)
                ]);
                DB::commit();
                return redirect()->back()->with('flash_message_success', 'Kata sandi berhasil diperbarui!');
            }
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Kata sandi gagal diperbarui!');
        }
    }

    public function updateTtl(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

        if ($request->tempat == "" || $request->tanggal == "" || $request->bulan == "" || $request->tahun == "") {
            return redirect()->back()->with('flash_message_error', 'Lengkapi data!');
        } else {
            DB::beginTransaction();
            try{
                $tempat = $request->tempat;
                $tgllahir = $request->tahun . '-' . $request->bulan . '-' . $request->tanggal;
                $tgl = $request->tanggal . '-' . $request->bulan . '-' . $request->tahun;
                $user = User::where('id', Auth::user()->id)->first();
                Activity::log(Auth::user()->id, 'Update', 'merubah tempat, tanggal lahir', 'Diperbarui menjadi Tempat Lahir: '.$tempat.', Tanggal Lahir: '. $tgl, 'Tempat, Tanggal lahir sebelumnya Tempat Lahir: '.$user->tempat_lahir.', Tanggal Lahir: '. date('d-m-Y', strtotime($user->tgl_lahir)), Carbon::now('Asia/Jakarta'));
                User::where('id', Auth::user()->id)->update([
                    'tempat_lahir' => $tempat,
                    'tgl_lahir' => $tgllahir
                ]);
                DB::commit();
                return redirect()->back()->with('flash_message_success', 'Tempat, tanggal lahir berhasil diperbarui!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect()->back()->with('flash_message_error', 'Tempat, tanggal lahir gagal diperbarui!');
            }
        }
    }

    public function updateAlamat(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

        if ($request->alamat == "") {
            return redirect()->back()->with('flash_message_error', 'Lengkapi data!');
        } else {
            DB::beginTransaction();
            try{
                $user = User::where('id', Auth::user()->id)->first();
                Activity::log(Auth::user()->id, 'Update', 'merubah alamat', 'Diperbarui menjadi ' . $request->alamat, 'Alamat sebelumnya ' . $user->address, Carbon::now('Asia/Jakarta'));
                User::where('id', Auth::user()->id)->update([
                    'address' => $request->alamat
                ]);
                DB::commit();
                return redirect()->back()->with('flash_message_success', 'Alamat berhasil diperbarui!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect()->back()->with('flash_message_error', 'Alamat gagal diperbarui!');
            }
        }
    }

    public function updateFoto(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }
        
        DB::beginTransaction();
        try{

            if ($request->hasFile('foto')) {
                $image_tmp = Input::file('foto');
                $file = $request->file('foto');
                $image_size = $image_tmp->getSize(); //getClientSize()
                $maxsize = '2097152';
                if ($image_size < $maxsize) {

                    if ($image_tmp->isValid()) {

                        $namefile = $request->current_img;

                        if ($namefile != "") {

                            $path = 'public/images/' . $namefile;

                            if (File::exists($path)) {
                                # code...
                                File::delete($path);
                            }

                        }

                        $extension = $image_tmp->getClientOriginalExtension();
                        $filename = date('YmdHms') . rand(111, 99999) . '.' . $extension;
                        $image_path = 'public/images';

                        if (!is_dir($image_path )) {
                            mkdir("public/images", 0777, true);
                        }

                        ini_set('memory_limit', '256M');
                        $file->move($image_path, $filename);
                        User::where('id', Auth::user()->id)->update(['img' => $filename]);
                        Activity::log(Auth::user()->id, 'Update', 'merubah foto profil', 'Foto profil telah diperbarui', null, Carbon::now('Asia/Jakarta'));
                        DB::commit();
                        return redirect()->back()->with('flash_message_success', 'Foto profil berhasil diperbarui!');
                    }
                } else {

                    return redirect()->back()->with('flash_message_error', 'Foto profil gagal diperbarui...! Ukuran file terlalu besar');

                }
            }

        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Foto profil gagal diperbarui!');
        }
    }
}
