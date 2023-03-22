using LukaKompControlPanel.Models;
using System;
using System.Collections.Generic;
using System.Threading;
using System.Windows.Forms;

using LukaKompControlPanel.Klase;
using System.Runtime.InteropServices;
using System.Drawing;
using System.Drawing.Text;
namespace LukaKompControlPanel
{
    public partial class Login : Form
    {
        private Thread th;

        public Login()
        {
            InitializeComponent();
        }



        private async void loginButton_Click(object sender, EventArgs e)
        {
            List<korisnik> korisnik1 = new List<korisnik>();

            /*Zovemo asinhronu metodu loadDataAsync koja vraca listu korisnika
            ali u ovom slucaju namt reba samo jedan korisnik ali ja ne znam da konvertujem
            ienumerable u objekat tako da cemo morati ovako*/
            korisnik1 = await dataAccess.LoadDataAsync<korisnik, dynamic>(
                "call loginProc(@username)",
                new { username = UsernameTextBox.Text },
                Helper.CnnVal("LukaKomp"));

            if (korisnik1.Count > 0)
            {
                //Koristimo cryptSharp ekstenziju kako bi smo checkovali da li su passwordi isti
                if (CryptSharp.Crypter.CheckPassword(passwordTextBox.Text, korisnik1[0].sifra))
                {
                    //Dopustamo samo administratorima da se uloguju :)
                    if (korisnik1[0].privilegija == "admin")
                    {
                        //Kreiramo novi thread u kojem cemo da pokrenemo dashBoard
                        th = new Thread(() => otvoriDasbBoard(korisnik1[0].id, korisnik1[0].username, korisnik1[0].email));
                        th.SetApartmentState(ApartmentState.STA);
                        th.Start();

                        this.Close();
                    }
                    else
                    {
                        MessageBox.Show("Na zalost nemate privilegiju da udjete na dashboard, kontaktirajte adminstratora");
                    }
                }
                else
                {
                    MessageBox.Show("Sifra nije ispravna");
                }
            }
            else
            {
                MessageBox.Show("Losi parametri!","Greska", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        public void otvoriDasbBoard(int id, string username, string email)
        {
            Application.Run(new Dashboard(id, username, email));
        }

        private void Login_Load(object sender, EventArgs e)
        {
            foreach(Control c in this.Controls)
            {
                c.Font = new Font(Design.fonts.Families[0], 15f, FontStyle.Regular);
            }
            label3.Font = new Font(Design.fonts.Families[0], 30);
        }

        private void exitButton_Click(object sender, EventArgs e)
        {
            this.Close();
        }

        private void exitButton_MouseMove(object sender, MouseEventArgs e)
        {
            exitButton.Cursor = Cursors.Hand;
        }

        Point lastPoint;
        private void Login_MouseMove(object sender, MouseEventArgs e)
        {
            if (e.Button == MouseButtons.Left)
            {
                this.Left += e.X - lastPoint.X;
                this.Top += e.Y - lastPoint.Y;
            }
        }

        private void Login_MouseDown(object sender, MouseEventArgs e)
        {
            lastPoint = new Point(e.X, e.Y);
        }
    }
}