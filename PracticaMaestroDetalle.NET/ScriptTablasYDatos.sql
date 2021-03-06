/*
NOTA:
ANTES DE CORRER ESTE SCRIPT, CREAR LA BASE DE DATOS CON EL NOMBRE GRUPO9DB
*/

USE [GRUPO9DB]
GO
/****** Object:  ForeignKey [FK_Inventario_Productos]    Script Date: 11/03/2015 03:53:05 ******/
IF  EXISTS (SELECT * FROM sys.foreign_keys WHERE object_id = OBJECT_ID(N'[dbo].[FK_Inventario_Productos]') AND parent_object_id = OBJECT_ID(N'[dbo].[Inventario]'))
ALTER TABLE [dbo].[Inventario] DROP CONSTRAINT [FK_Inventario_Productos]
GO
/****** Object:  Table [dbo].[Inventario]    Script Date: 11/03/2015 03:53:05 ******/
IF  EXISTS (SELECT * FROM sys.foreign_keys WHERE object_id = OBJECT_ID(N'[dbo].[FK_Inventario_Productos]') AND parent_object_id = OBJECT_ID(N'[dbo].[Inventario]'))
ALTER TABLE [dbo].[Inventario] DROP CONSTRAINT [FK_Inventario_Productos]
GO
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[Inventario]') AND type in (N'U'))
DROP TABLE [dbo].[Inventario]
GO
/****** Object:  Table [dbo].[Productos]    Script Date: 11/03/2015 03:53:05 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[Productos]') AND type in (N'U'))
DROP TABLE [dbo].[Productos]
GO
/****** Object:  Table [dbo].[Productos]    Script Date: 11/03/2015 03:53:05 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[Productos]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[Productos](
	[Producto] [nchar](10) NOT NULL,
	[Nombre] [nchar](100) NULL,
	[Descripcion] [nchar](300) NULL,
	[FechaIngreso] [date] NULL,
 CONSTRAINT [PK_Productos] PRIMARY KEY CLUSTERED 
(
	[Producto] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END
GO
/****** Object:  Table [dbo].[Inventario]    Script Date: 11/03/2015 03:53:05 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[Inventario]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[Inventario](
	[Movimiento] [int] IDENTITY(1,1) NOT NULL,
	[Producto] [nchar](10) NULL,
	[TipoMovimiento] [smallint] NULL,
	[Fecha] [date] NULL,
	[Cantidad] [numeric](18, 3) NULL,
 CONSTRAINT [PK_Inventario] PRIMARY KEY CLUSTERED 
(
	[Movimiento] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END
GO
/****** Object:  ForeignKey [FK_Inventario_Productos]    Script Date: 11/03/2015 03:53:05 ******/
IF NOT EXISTS (SELECT * FROM sys.foreign_keys WHERE object_id = OBJECT_ID(N'[dbo].[FK_Inventario_Productos]') AND parent_object_id = OBJECT_ID(N'[dbo].[Inventario]'))
ALTER TABLE [dbo].[Inventario]  WITH CHECK ADD  CONSTRAINT [FK_Inventario_Productos] FOREIGN KEY([Producto])
REFERENCES [dbo].[Productos] ([Producto])
GO
IF  EXISTS (SELECT * FROM sys.foreign_keys WHERE object_id = OBJECT_ID(N'[dbo].[FK_Inventario_Productos]') AND parent_object_id = OBJECT_ID(N'[dbo].[Inventario]'))
ALTER TABLE [dbo].[Inventario] CHECK CONSTRAINT [FK_Inventario_Productos]
GO
