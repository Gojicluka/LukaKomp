using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

using LukaKompControlPanel.Models;
using LukaKompControlPanel.Klase;
namespace LukaKompControlPanel
{
    public partial class Statistika : Form
    {
        Point lastPoint;

        public Statistika()
        {
            InitializeComponent();
        }

        private void Statistika_Load(object sender, EventArgs e)
        {
            List<racun> racuni = new List<racun>();
            racuni = dataAccess.LoadData<racun, dynamic>
                ("SELECT SUM(medj.kolicina) as ukProdato,SUM(kom.cena*medj.kolicina) as ukcena FROM racun " +
                "INNER JOIN racun_medjutabela as medj ON racun.id = medj.idracun " +
                "INNER JOIN komponente as kom ON medj.idDrugeTabele = kom.id ", new { }, Helper.CnnVal("LukaKomp"));


            label3.Text = racuni[0].ukProdato.ToString();
            label4.Text = racuni[0].ukCena.ToString() + " din";

            foreach (Control c in this.Controls)
            {
                c.Font = new Font(Design.fonts.Families[0], 15f, FontStyle.Regular);
            }
            label5.Font = new Font(Design.fonts.Families[0], 35);
        }

        private void Statistika_MouseDown(object sender, MouseEventArgs e)
        {
            lastPoint = new Point(e.X, e.Y);
        }

        private void Statistika_MouseMove(object sender, MouseEventArgs e)
        {
            if (e.Button == MouseButtons.Left)
            {
                this.Left += e.X - lastPoint.X;
                this.Top += e.Y - lastPoint.Y;
            }
        }

        private void exitButton_Click(object sender, EventArgs e)
        {
            this.Close();
        }

        private void label3_Click(object sender, EventArgs e)
        {

        }
    }
}
