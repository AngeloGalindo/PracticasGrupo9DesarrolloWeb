namespace ConsumeWS
{
    partial class frmConsumeWS
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
            this.txtArabigo = new System.Windows.Forms.TextBox();
            this.txtRomano = new System.Windows.Forms.TextBox();
            this.btnTransforma = new System.Windows.Forms.Button();
            this.SuspendLayout();
            // 
            // txtArabigo
            // 
            this.txtArabigo.Location = new System.Drawing.Point(29, 75);
            this.txtArabigo.Name = "txtArabigo";
            this.txtArabigo.Size = new System.Drawing.Size(100, 20);
            this.txtArabigo.TabIndex = 0;
            // 
            // txtRomano
            // 
            this.txtRomano.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(255)))), ((int)(((byte)(255)))), ((int)(((byte)(128)))));
            this.txtRomano.Enabled = false;
            this.txtRomano.Location = new System.Drawing.Point(244, 75);
            this.txtRomano.Name = "txtRomano";
            this.txtRomano.Size = new System.Drawing.Size(100, 20);
            this.txtRomano.TabIndex = 1;
            // 
            // btnTransforma
            // 
            this.btnTransforma.Location = new System.Drawing.Point(144, 74);
            this.btnTransforma.Name = "btnTransforma";
            this.btnTransforma.Size = new System.Drawing.Size(86, 23);
            this.btnTransforma.TabIndex = 2;
            this.btnTransforma.Text = "Transforma >>";
            this.btnTransforma.UseVisualStyleBackColor = true;
            this.btnTransforma.Click += new System.EventHandler(this.btnTransforma_Click);
            // 
            // frmConsumeWS
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(356, 266);
            this.Controls.Add(this.btnTransforma);
            this.Controls.Add(this.txtRomano);
            this.Controls.Add(this.txtArabigo);
            this.Name = "frmConsumeWS";
            this.Text = "Transforma de Arabigo a Romano";
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.TextBox txtArabigo;
        private System.Windows.Forms.TextBox txtRomano;
        private System.Windows.Forms.Button btnTransforma;
    }
}

