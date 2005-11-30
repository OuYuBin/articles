import org.eclipse.swt.*;
import org.eclipse.swt.widgets.*;

public class FileDialogMain {

public static void main(String[] args) {
	Display display = new Display();
	Shell shell = new Shell(display);
	shell.setText("Main");
	Menu mb = new Menu(shell, SWT.BAR);
	shell.setMenuBar(mb);
	shell.open();

	FileDialog dialog = new FileDialog(shell, SWT.OPEN);
	String name = dialog.open();
	shell.setText(name);
		
	while (!shell.isDisposed()) {
		if (!display.readAndDispatch())
			display.sleep();
	}
	display.dispose();
}
}
