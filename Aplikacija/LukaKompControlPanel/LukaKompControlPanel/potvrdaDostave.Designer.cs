
namespace LukaKompControlPanel
{
    partial class potvrdaDostave
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.dataGridView1 = new System.Windows.Forms.DataGridView();
            this.id = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.korisnik = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.datum_naruceno = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.datum_dostavljeno = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.ukupna_cena = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.dostavljeno = new System.Windows.Forms.DataGridViewCheckBoxColumn();
            this.exitButton = new System.Windows.Forms.PictureBox();
            this.button1 = new System.Windows.Forms.Button();
            ((System.ComponentModel.ISupportInitialize)(this.dataGridView1)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.exitButton)).BeginInit();
            this.SuspendLayout();
            // 
            // dataGridView1
            // 
            this.dataGridView1.BackgroundColor = System.Drawing.Color.FromArgb(((int)(((byte)(245)))), ((int)(((byte)(243)))), ((int)(((byte)(243)))));
            this.dataGridView1.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            this.dataGridView1.Columns.AddRange(new System.Windows.Forms.DataGridViewColumn[] {
            this.id,
            this.korisnik,
            this.datum_naruceno,
            this.datum_dostavljeno,
            this.ukupna_cena,
            this.dostavljeno});
            this.dataGridView1.Location = new System.Drawing.Point(-3, 0);
            this.dataGridView1.Name = "dataGridView1";
            this.dataGridView1.Size = new System.Drawing.Size(594, 259);
            this.dataGridView1.TabIndex = 0;
            this.dataGridView1.CellContentClick += new System.Windows.Forms.DataGridViewCellEventHandler(this.dataGridView1_CellContentClick);
            // 
            // id
            // 
            this.id.HeaderText = "id";
            this.id.Name = "id";
            this.id.ReadOnly = true;
            this.id.Width = 50;
            // 
            // korisnik
            // 
            this.korisnik.HeaderText = "korisnik";
            this.korisnik.Name = "korisnik";
            this.korisnik.ReadOnly = true;
            // 
            // datum_naruceno
            // 
            this.datum_naruceno.HeaderText = "datum_naruceno";
            this.datum_naruceno.Name = "datum_naruceno";
            this.datum_naruceno.ReadOnly = true;
            // 
            // datum_dostavljeno
            // 
            this.datum_dostavljeno.HeaderText = "datum_dostavljeno";
            this.datum_dostavljeno.Name = "datum_dostavljeno";
            this.datum_dostavljeno.ReadOnly = true;
            // 
            // ukupna_cena
            // 
            this.ukupna_cena.HeaderText = "Ukupna cena";
            this.ukupna_cena.Name = "ukupna_cena";
            this.ukupna_cena.ReadOnly = true;
            // 
            // dostavljeno
            // 
            this.dostavljeno.HeaderText = "dostavljeno";
            this.dostavljeno.Name = "dostavljeno";
            // 
            // exitButton
            // 
            this.exitButton.BackColor = System.Drawing.Color.Transparent;
            this.exitButton.Image = global::LukaKompControlPanel.Properties.Resources._215904_200;
            this.exitButton.Location = new System.Drawing.Point(632, 0);
            this.exitButton.Name = "exitButton";
            this.exitButton.Size = new System.Drawing.Size(30, 30);
            this.exitButton.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage;
            this.exitButton.TabIndex = 13;
            this.exitButton.TabStop = false;
            this.exitButton.Click += new System.EventHandler(this.exitButton_Click);
            // 
            // button1
            // 
            this.button1.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(30)))), ((int)(((byte)(168)))), ((int)(((byte)(150)))));
            this.button1.FlatStyle = System.Windows.Forms.FlatStyle.Popup;
            this.button1.Font = new System.Drawing.Font("Microsoft Sans Serif", 15F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.button1.ForeColor = System.Drawing.SystemColors.Control;
            this.button1.Location = new System.Drawing.Point(597, 221);
            this.button1.Name = "button1";
            this.button1.Size = new System.Drawing.Size(65, 38);
            this.button1.TabIndex = 1;
            this.button1.Text = "OK";
            this.button1.UseVisualStyleBackColor = false;
            this.button1.Click += new System.EventHandler(this.button1_Click);
            // 
            // potvrdaDostave
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(245)))), ((int)(((byte)(243)))), ((int)(((byte)(243)))));
            this.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Stretch;
            this.ClientSize = new System.Drawing.Size(665, 262);
            this.Controls.Add(this.exitButton);
            this.Controls.Add(this.button1);
            this.Controls.Add(this.dataGridView1);
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.None;
            this.Name = "potvrdaDostave";
            this.Text = "potvrdaDostave";
            this.Load += new System.EventHandler(this.potvrdaDostave_Load);
            this.MouseDown += new System.Windows.Forms.MouseEventHandler(this.potvrdaDostave_MouseDown);
            this.MouseMove += new System.Windows.Forms.MouseEventHandler(this.potvrdaDostave_MouseMove);
            ((System.ComponentModel.ISupportInitialize)(this.dataGridView1)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.exitButton)).EndInit();
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.DataGridView dataGridView1;
        private System.Windows.Forms.Button button1;
        private System.Windows.Forms.PictureBox exitButton;
        private System.Windows.Forms.DataGridViewTextBoxColumn id;
        private System.Windows.Forms.DataGridViewTextBoxColumn korisnik;
        private System.Windows.Forms.DataGridViewTextBoxColumn datum_naruceno;
        private System.Windows.Forms.DataGridViewTextBoxColumn datum_dostavljeno;
        private System.Windows.Forms.DataGridViewTextBoxColumn ukupna_cena;
        private System.Windows.Forms.DataGridViewCheckBoxColumn dostavljeno;
    }
}